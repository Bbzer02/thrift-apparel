<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File as FileRule;

class ShopAuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.unique' => 'This email already exists. Please sign in instead.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // Keep self-signup accounts as buyers by default.
            // Admin users should be created explicitly (seed/manual update).
            'is_admin' => false,
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'Account created successfully.',
            'profile' => $this->profilePayload($user),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid email or password.'], 422);
            }

            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Signed in successfully.',
                'profile' => $this->profilePayload($user),
                'csrf_token' => csrf_token(),
            ]);
        }

        return redirect()->intended(route('shop'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully.',
            'csrf_token' => csrf_token(),
        ]);
    }

    public function profile(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'profile' => $this->profilePayload($user),
        ]);
    }

    public function createOrder(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
        ]);
        $shippingAddress = collect($user->addresses ?? [])
            ->map(fn ($line) => trim((string) $line))
            ->first();

        $order = Order::create([
            'user_id' => $user->id,
            'item_id' => $validated['item_id'] ?? null,
            'item_name' => $validated['item_name'],
            'shipping_address' => $shippingAddress ?: 'No address provided',
            'status' => 'to_ship',
        ]);

        return response()->json([
            'message' => 'Order created successfully.',
            'order' => $order,
        ], 201);
    }

    public function addAddress(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'address' => ['required', 'string', 'max:255'],
        ]);

        $addresses = collect($user->addresses ?? [])
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values();

        $addresses->prepend(trim($validated['address']));
        $addresses = $addresses->unique()->take(5)->values();

        $user->update([
            'addresses' => $addresses->all(),
        ]);

        return response()->json([
            'message' => 'Address saved successfully.',
            'addresses' => $addresses->all(),
        ]);
    }

    public function addProduct(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Only admin can add products.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'size' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:1'],
            'category' => ['required', 'string', 'max:50'],
            'remove_bg' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['nullable', FileRule::image()->max(4096)],
            'existing_image_urls' => ['nullable', 'array'],
            'existing_image_urls.*' => ['nullable', 'string', 'max:2048'],
        ]);

        $existingImageUrls = collect($validated['existing_image_urls'] ?? [])
            ->map(fn ($url) => trim((string) $url))
            ->filter()
            ->values()
            ->all();
        $uploadedImageUrls = $this->storeUploadedImages(
            $request->file('images', []),
            (bool) ($validated['remove_bg'] ?? false)
        );
        $imageUrls = array_values(array_unique([...$existingImageUrls, ...$uploadedImageUrls]));
        if (!count($imageUrls)) {
            return response()->json(['message' => 'Please upload at least one product photo.'], 422);
        }

        $item = Item::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'size' => $validated['size'],
            'price' => $validated['price'],
            'condition' => 'good',
            'category' => $validated['category'],
            'image_url' => $imageUrls[0],
            'is_available' => true,
        ]);
        if (Schema::hasColumn('items', 'image_urls')) {
            $item->update(['image_urls' => $imageUrls]);
        }

        return response()->json([
            'message' => 'Product added successfully.',
            'product' => $item->fresh(),
        ], 201);
    }

    public function listProducts()
    {
        $products = Item::query()
            ->latest()
            ->get()
            ->map(function (Item $item) {
                $images = collect($item->image_urls ?? [])
                    ->map(fn ($url) => trim((string) $url))
                    ->filter()
                    ->values();
                if ($images->isEmpty() && $item->image_url) {
                    $images->push($item->image_url);
                }
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'size' => $item->size,
                    'price' => $item->price,
                    'category' => $item->category,
                    'image_url' => $images->first(),
                    'image_urls' => $images->all(),
                ];
            })
            ->values();

        return response()->json(['products' => $products]);
    }

    public function updateProduct(Request $request, Item $item)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Only admin can update products.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'size' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:1'],
            'category' => ['required', 'string', 'max:50'],
            'remove_bg' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['nullable', FileRule::image()->max(4096)],
            'removed_images' => ['nullable', 'array'],
            'removed_images.*' => ['nullable', 'string', 'max:2048'],
        ]);

        $updates = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'size' => $validated['size'],
            'price' => $validated['price'],
            'category' => $validated['category'],
        ];

        $existingImages = collect([]);
        if (Schema::hasColumn('items', 'image_urls')) {
            $existingImages = collect($item->image_urls ?? [])
                ->map(fn ($url) => trim((string) $url))
                ->filter()
                ->values();
        }
        if ($existingImages->isEmpty() && $item->image_url) {
            $existingImages->push($item->image_url);
        }
        $removedImages = collect($validated['removed_images'] ?? [])
            ->map(fn ($url) => trim((string) $url))
            ->filter()
            ->values()
            ->all();

        $keptImages = $existingImages
            ->reject(fn ($url) => in_array($url, $removedImages, true))
            ->values()
            ->all();
        $newImages = $this->storeUploadedImages(
            $request->file('images', []),
            (bool) ($validated['remove_bg'] ?? false)
        );
        $finalImages = array_values(array_unique([...$keptImages, ...$newImages]));

        if (!count($finalImages)) {
            return response()->json(['message' => 'At least one product photo is required.'], 422);
        }

        $updates['image_url'] = $finalImages[0];
        if (Schema::hasColumn('items', 'image_urls')) {
            $updates['image_urls'] = $finalImages;
        }

        $item->update($updates);

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $item->fresh(),
        ]);
    }

    public function deleteProduct(Request $request, Item $item)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Only admin can delete products.'], 403);
        }

        $item->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }

    public function adminOrders(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Only admin can view orders.'], 403);
        }

        $orders = Order::query()
            ->with(['user:id,name,email,addresses', 'item:id,name'])
            ->latest()
            ->get()
            ->map(function (Order $order) {
                $fallbackAddress = null;
                if ($order->user && is_array($order->user->addresses) && count($order->user->addresses)) {
                    $fallbackAddress = $order->user->addresses[0];
                }
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'user' => $order->user ? [
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'email' => $order->user->email,
                    ] : null,
                    'item' => [
                        'name' => $order->item?->name ?? $order->item_name,
                    ],
                    'shipping_address' => $order->shipping_address ?: ($fallbackAddress ?: 'No address provided'),
                ];
            })
            ->values();

        return response()->json(['orders' => $orders]);
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || !$user->is_admin) {
            return response()->json(['message' => 'Only admin can update orders.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['to_ship', 'to_receive'])],
        ]);

        if ($order->status === 'received') {
            return response()->json(['message' => 'Received orders can no longer be changed.'], 422);
        }

        $order->update(['status' => $validated['status']]);

        return response()->json(['message' => 'Order status updated.']);
    }

    public function receiveOrder(Request $request, Order $order)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if ($order->status !== 'to_receive') {
            return response()->json(['message' => 'Order is not ready to receive.'], 422);
        }

        $order->update(['status' => 'received']);
        $order->loadMissing(['user:id,name,email,addresses', 'item:id,name']);

        return response()->json([
            'message' => 'Order marked as received.',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'user' => $order->user ? [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ] : null,
                'item' => [
                    'name' => $order->item?->name ?? $order->item_name,
                ],
                'shipping_address' => $order->shipping_address ?: (
                    is_array($order->user?->addresses) && count($order->user->addresses)
                        ? $order->user->addresses[0]
                        : 'No address provided'
                ),
            ],
        ]);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if ($order->status !== 'to_ship') {
            return response()->json(['message' => 'Only waiting orders can be cancelled.'], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Order cancelled successfully.']);
    }

    public function deleteOrder(Request $request, Order $order)
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user || $order->user_id !== $user->id) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        if (!in_array($order->status, ['cancelled', 'received'], true)) {
            return response()->json(['message' => 'Only cancelled or received orders can be deleted.'], 422);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully.']);
    }

    /**
     * @param array<int, \Illuminate\Http\UploadedFile|null> $files
     * @return array<int, string>
     */
    private function storeUploadedImages(array $files, bool $removeBackground = false): array
    {
        return collect($files)
            ->filter()
            ->map(function ($file) use ($removeBackground) {
                if (!$removeBackground) {
                    return '/storage/' . $file->store('products', 'public');
                }

                $relativeOutputPath = 'products/' . Str::uuid() . '-nobg.png';
                $absoluteOutputPath = storage_path('app/public/' . $relativeOutputPath);
                $absoluteOutputDir = dirname($absoluteOutputPath);
                if (!is_dir($absoluteOutputDir)) {
                    mkdir($absoluteOutputDir, 0775, true);
                }

                $result = Process::path(base_path())->run([
                    'node',
                    'tools/remove-bg.mjs',
                    $file->getRealPath(),
                    $absoluteOutputPath,
                ]);

                if ($result->successful() && file_exists($absoluteOutputPath)) {
                    return '/storage/' . $relativeOutputPath;
                }

                return '/storage/' . $file->store('products', 'public');
            })
            ->values()
            ->all();
    }

    private function profilePayload(User $user): array
    {
        $orders = Order::query()->where('user_id', $user->id)->get();
        $toShip = $orders->where('status', 'to_ship')->count();
        $toReceive = $orders->where('status', 'to_receive')->count();
        $received = $orders->where('status', 'received')->count();
        $ordersList = Order::query()
            ->where('user_id', $user->id)
            ->with('item:id,name')
            ->latest()
            ->get()
            ->map(function (Order $order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'item' => [
                        'name' => $order->item?->name ?? $order->item_name,
                    ],
                    'shipping_address' => $order->shipping_address,
                ];
            })
            ->values();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => (bool) $user->is_admin,
            'addresses' => $user->addresses && count($user->addresses)
                ? $user->addresses
                : ['Add your first shipping address.'],
            'orders' => [
                'to_ship' => $toShip,
                'to_receive' => $toReceive,
                'received' => $received,
            ],
            'orders_list' => $ordersList,
        ];
    }
}

