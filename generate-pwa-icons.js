#!/usr/bin/env node

/**
 * Script pour générer les icônes PWA à partir d'une image source
 * 
 * Installation: npm install sharp
 * Usage: node generate-pwa-icons.js [source-image]
 * 
 * Exemple: node generate-pwa-icons.js logo.png
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Vérifier si sharp est installé
let sharp;
try {
  const sharpModule = await import('sharp');
  sharp = sharpModule.default || sharpModule;
} catch (e) {
  console.error('❌ sharp n\'est pas installé.');
  console.error('Installez-le avec: npm install sharp');
  process.exit(1);
}

const sourceFile = process.argv[2] || 'logo.png';
const outputDir = path.join(__dirname, 'public', 'images');

// Créer le dossier de destination s'il n'existe pas
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
  console.log(`📁 Dossier créé: ${outputDir}`);
}

// Vérifier que le fichier source existe
if (!fs.existsSync(sourceFile)) {
  console.error(`❌ Fichier source non trouvé: ${sourceFile}`);
  console.error('Créez d\'abord un fichier logo.png ou spécifiez le chemin correct.');
  process.exit(1);
}

console.log(`\n🎨 Génération des icônes PWA à partir de: ${sourceFile}\n`);

const tasks = [
  { size: 192, name: 'icon-192.png', maskable: false },
  { size: 512, name: 'icon-512.png', maskable: false },
  { size: 192, name: 'icon-192-maskable.png', maskable: true },
  { size: 512, name: 'icon-512-maskable.png', maskable: true },
];

await Promise.all(
  tasks.map(async (task) => {
    const outputPath = path.join(outputDir, task.name);
    const size = task.size;

    try {
      if (task.maskable) {
        // Pour les icônes maskable, ajouter du padding pour l'adaptation
        await sharp(sourceFile)
          .resize(size * 0.8, size * 0.8, {
            fit: 'contain',
            background: { r: 0, g: 0, b: 0, alpha: 0 }
          })
          .extend({
            top: Math.ceil(size * 0.1),
            bottom: Math.ceil(size * 0.1),
            left: Math.ceil(size * 0.1),
            right: Math.ceil(size * 0.1),
            background: { r: 0, g: 0, b: 0, alpha: 0 }
          })
          .png()
          .toFile(outputPath);
      } else {
        // Pour les icônes standard
        await sharp(sourceFile)
          .resize(size, size, {
            fit: 'cover',
            position: 'center'
          })
          .png()
          .toFile(outputPath);
      }

      const type = task.maskable ? '(maskable)' : '(standard)';
      console.log(`✅ ${task.name} ${type} - ${size}x${size} px`);
    } catch (err) {
      console.error(`❌ Erreur pour ${task.name}:`, err.message);
    }
  })
);

console.log('\n✨ Icônes PWA générées avec succès!');
console.log(`📍 Emplacement: ${outputDir}\n`);

