# 🧪 TASK-002: Crear tests para módulo financiero

**ID:** TASK-002  
**Fase:** 1 - Estabilización  
**Tipo:** 🧪 Test  
**Prioridad:** 🔴 Alta  
**Estado:** 📝 Por hacer  
**Estimación:** 8 horas  
**Creada:** 2026-03-24  
**Responsable:** rangerdev  
**Dependencias:** TASK-001  

---

## 🎯 OBJETIVO

Crear suite de tests completa para el módulo financiero, alcanzando 80%+ de cobertura y asegurando la integridad de transacciones financieras.

## 📋 SUBTAREAS

- [ ] **Crear estructura de tests**
  - [ ] Crear `tests/Feature/Financial/ReceivableTest.php`
  - [ ] Crear `tests/Feature/Financial/ExpenseTest.php`
  - [ ] Crear `tests/Feature/Financial/CashTransactionTest.php`
  - [ ] Crear `tests/Feature/Financial/CashAccountTest.php`

- [ ] **Tests para Receivable (cobros)**
  - [ ] Test creación de cobro
  - [ ] Test pago de cobro
  - [ ] Test cálculo de intereses moratorios
  - [ ] Test reportes de cobranza

- [ ] **Tests para Expense (gastos)**
  - [ ] Test creación de gasto
  - [ ] Test aprobación de gasto
  - [ ] Test cierre mensual de gastos
  - [ ] Test reportes de gastos

- [ ] **Tests para CashTransaction (transacciones)**
  - [ ] Test depósito
  - [ ] Test retiro
  - [ ] Test transferencia entre cuentas
  - [ ] Test reversión de transacción

- [ ] **Tests de integración**
  - [ ] Test flujo completo: Cobro → Pago → Cierre
  - [ ] Test conciliación bancaria
  - [ ] Test generación de reportes financieros

- [ ] **Tests de seguridad**
  - [ ] Test permisos por rol (admin/residente)
  - [ ] Test validación de montos
  - [ ] Test auditoría de transacciones

## 📁 ARCHIVOS AFECTADOS

```
tests/Feature/Financial/
app/Models/Receivable.php
app/Models/Expense.php
app/Models/CashTransaction.php
app/Models/CashAccount.php
app/Http/Controllers/Admin/Financial/*.php
```

## ✅ CRITERIOS DE COMPLETACIÓN

- [ ] 80%+ cobertura en módulo financiero
- [ ] Todos los tests pasando
- [ ] Tests de integración funcionando
- [ ] Documentación de tests en `TESTING_GUIDE.md`

---

**Última actualización:** 2026-03-24