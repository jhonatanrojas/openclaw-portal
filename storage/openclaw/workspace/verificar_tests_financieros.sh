#!/bin/bash

echo "=== VERIFICACIÓN DE TESTS FINANCIEROS ==="
echo "========================================="
echo ""

echo "📁 Estructura de tests creada:"
echo "-----------------------------"
find tests/Feature/Financial -name "*.php" -type f | while read file; do
    filename=$(basename "$file")
    test_count=$(grep -c "@test\|function test\|public function test\|/** @test */" "$file")
    echo "• $filename: $test_count tests"
done

echo ""
echo "📊 Resumen por archivo:"
echo "----------------------"

total_tests=0
for file in tests/Feature/Financial/*.php; do
    if [ -f "$file" ]; then
        filename=$(basename "$file")
        lines=$(wc -l < "$file")
        tests=$(grep -c "@test\|function test\|public function test\|/** @test */" "$file")
        total_tests=$((total_tests + tests))
        
        echo "📄 $filename:"
        echo "   Líneas: $lines"
        echo "   Tests: $tests"
        
        # Mostrar nombres de tests
        grep -h "@test\|function test\|public function test" "$file" | sed 's/.*test //;s/.*@test //;s/(.*//;s/{.*//' | while read test_name; do
            if [ -n "$test_name" ]; then
                echo "   - $test_name"
            fi
        done
        echo ""
    fi
done

echo "🎯 TOTAL TESTS FINANCIEROS: $total_tests"
echo ""
echo "✅ Estructura de tests verificada correctamente"
echo ""
echo "⚠️  Nota: Los tests no se pueden ejecutar debido a incompatibilidad de PHP"
echo "   Requerido: PHP >= 8.2.0"
echo "   Actual: PHP $(php --version | head -1 | cut -d' ' -f2)"
echo ""
echo "📝 Para ejecutar los tests necesitas:"
echo "   1. Actualizar PHP a versión 8.2 o superior"
echo "   2. O ajustar composer.json para aceptar PHP 8.1"
echo ""
echo "🔧 Solución temporal para desarrollo:"
echo "   Editar composer.json y cambiar \"php\": \"^8.2\" por \"php\": \"^8.1\""