import { readFile, writeFile } from "node:fs/promises";
import { removeBackground } from "@imgly/background-removal-node";

const inputPath = process.argv[2];
const outputPath = process.argv[3];

if (!inputPath || !outputPath) {
  console.error("Usage: node tools/remove-bg.mjs <input> <output>");
  process.exit(1);
}

const inputBuffer = await readFile(inputPath);
const inputBlob = new Blob([inputBuffer], { type: "image/png" });
const resultBlob = await removeBackground(inputBlob);
const outputBuffer = Buffer.from(await resultBlob.arrayBuffer());
await writeFile(outputPath, outputBuffer);
console.log(`Background removed: ${outputPath}`);
