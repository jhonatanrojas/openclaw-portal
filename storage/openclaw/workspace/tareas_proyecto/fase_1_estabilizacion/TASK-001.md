# 🗑️ TASK-001: Eliminar código legacy auth components

**ID:** TASK-001  
**Fase:** 1 - Estabilización  
**Tipo:** 🔧 Refactor  
**Prioridad:** 🔴 Alta  
**Estado:** 📝 Por hacer  
**Estimación:** 2 horas  
**Creada:** 2026-03-24  
**Responsable:** rangerdev  
**Dependencias:** Ninguna  

---

## 🎯 OBJETIVO

Eliminar componentes Vue legacy de autenticación y asegurar que el sistema funcione correctamente con los componentes actualizados.

## 📋 SUBTAREAS

- [ ] **Identificar componentes legacy**
  - [ ] Listar archivos `*Old.vue` en `resources/js/Pages/Auth/`
  - [ ] Verificar referencias en otros componentes
  - [ ] Documentar qué hace cada componente legacy

- [ ] **Eliminar archivos legacy**
  - [ ] Eliminar `ForgotPasswordOld.vue`
  - [ ] Eliminar `LoginOld.vue`  
  - [ ] Eliminar `RegisterOld.vue`
  - [ ] Verificar si hay otros archivos `*Old.vue`

- [ ] **Actualizar rutas**
  - [ ] Revisar `routes/auth.php`
  - [ ] Verificar que todas las rutas apunten a componentes actualizados
  - [ ] Actualizar cualquier referencia a componentes legacy

- [ ] **Verificar funcionalidad**
  - [ ] Probar login con usuario de prueba
  - [ ] Probar registro de nuevo usuario
  - [ ] Probar recuperación de contraseña
  - [ ] Verificar redirecciones correctas

- [ ] **Limpiar referencias**
  - [ ] Buscar y eliminar imports de componentes legacy
  - [ ] Actualizar cualquier referencia en documentación
  - [ ] Verificar que no haya broken links

## 📁 ARCHIVOS AFECTADOS

### **Para eliminar:**
```
resources/js/Pages/Auth/ForgotPasswordOld.vue
resources/js/Pages/Auth/LoginOld.vue
resources/js/Pages/Auth/RegisterOld.vue
```

### **Para verificar:**
```
routes/auth.php
resources/js/Pages/Auth/*.vue (componentes actualizados)
app/Http/Controllers/Auth/*.php
config/auth.php
```

## 🧪 PRUEBAS REQUERIDAS

### **Pruebas manuales:**
- [ ] Login con credenciales válidas
- [ ] Login con credenciales inválidas
- [ ] Registro de nuevo usuario
- [ ] Recuperación de contraseña
- [ ] Logout y redirección

### **Verificaciones:**
- [ ] No hay errores en consola del navegador
- [ ] Estilos se renderizan correctamente
- [ ] Mensajes de error son claros
- [ ] Responsive design funciona

## ⚠️ RIESGOS

1. **Componentes actualizados podrían tener bugs** no detectados
2. **Dependencias no documentadas** en componentes legacy
3. **Estilos podrían romperse** si hay CSS específico

## 🛡️ MITIGACIÓN DE RIESGOS

1. **Backup antes de eliminar:** `git commit -m "Backup antes de eliminar legacy auth"`
2. **Pruebas exhaustivas** después de cada eliminación
3. **Revisar git diff** para asegurar que solo se elimina lo necesario

## 📝 NOTAS

- Los componentes `*Improved.vue` o sin sufijo son los actualizados
- Verificar si hay diferencias significativas entre Old y Actual
- Considerar migrar cualquier funcionalidad útil de Old a Actual si es necesario

## 🔗 DEPENDENCIAS

- **TASK-002:** Tests para módulo financiero (depende de esta tarea)
- **TASK-003:** Tests para módulo de votaciones (depende de esta tarea)
- **TASK-005:** Refactorizar modelo User (depende de esta tarea)

## 📊 MÉTRICAS DE ÉXITO

- [ ] **0 archivos** `*Old.vue` en el proyecto
- [ ] **100% funcionalidad** auth trabajando
- [ ] **0 errores** en consola del navegador
- [ ] **Tiempo de carga** igual o mejor que antes

---

## 🗓️ SEGUIMIENTO

| Fecha | Estado | Horas | Comentarios |
|-------|--------|-------|-------------|
| 2026-03-24 | 📝 Por hacer | 0 | Creada la tarea |
| | | | |
| | | | |
| | | | |

## ✅ CRITERIOS DE COMPLETACIÓN

- [ ] Todos los archivos `*Old.vue` eliminados
- [ ] Rutas actualizadas correctamente
- [ ] Funcionalidad auth 100% operativa
- [ ] Commit con cambios: `git commit -m "TASK-001: Eliminado código legacy auth"`
- [ ] Actualizar este archivo con estado ✅ Completado

---

**Última actualización:** 2026-03-24  
**Próxima revisión:** 2026-03-25