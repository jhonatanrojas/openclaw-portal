<?php

namespace App\Console\Commands;

use App\Models\Documentation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportOpenClawDocs extends Command
{
    protected $signature = 'openclaw:import-docs';
    protected $description = 'Importar documentación existente de OpenClaw';

    public function handle()
    {
        $this->info('📦 Importando documentación de OpenClaw...');
        
        $docsPath = storage_path('openclaw/workspace');
        
        if (!file_exists($docsPath)) {
            $this->error('❌ Directorio de documentación no encontrado: ' . $docsPath);
            return 1;
        }
        
        $files = [
            'AGENTS.md' => ['title' => 'AGENTS.md - Guía para Agentes', 'category' => 'agents'],
            'SOUL.md' => ['title' => 'SOUL.md - Personalidad del Asistente', 'category' => 'general'],
            'USER.md' => ['title' => 'USER.md - Información del Usuario', 'category' => 'general'],
            'TOOLS.md' => ['title' => 'TOOLS.md - Configuración de Herramientas', 'category' => 'configuration'],
            'MEMORY.md' => ['title' => 'MEMORY.md - Memoria a Largo Plazo', 'category' => 'agents'],
            'HEARTBEAT.md' => ['title' => 'HEARTBEAT.md - Tareas Periódicas', 'category' => 'configuration'],
        ];
        
        $imported = 0;
        $skipped = 0;
        
        foreach ($files as $filename => $meta) {
            $filepath = $docsPath . '/' . $filename;
            
            if (!file_exists($filepath)) {
                $this->warn("⚠️ Archivo no encontrado: $filename");
                $skipped++;
                continue;
            }
            
            $content = file_get_contents($filepath);
            $slug = Str::slug($meta['title']);
            
            // Verificar si ya existe
            $existing = Documentation::where('slug', $slug)->first();
            
            if ($existing) {
                $this->line("↪️ Actualizando: {$meta['title']}");
                $existing->update([
                    'content' => $content,
                    'category' => $meta['category'],
                ]);
            } else {
                $this->line("✅ Importando: {$meta['title']}");
                Documentation::create([
                    'title' => $meta['title'],
                    'slug' => $slug,
                    'content' => $content,
                    'category' => $meta['category'],
                    'version' => '1.0',
                    'is_active' => true,
                ]);
            }
            
            $imported++;
        }
        
        $this->newLine();
        $this->info("🎉 Importación completada!");
        $this->line("📊 Estadísticas:");
        $this->line("   • Importados: $imported documentos");
        $this->line("   • Omitidos: $skipped documentos");
        
        return 0;
    }
}
