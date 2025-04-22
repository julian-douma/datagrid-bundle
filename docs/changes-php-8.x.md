## ⚙️ Skrepr Datagrid Migration Guide (PHP 8+)

**Old way (PHP <8.0):**
- Used `@Datagrid` and `@DoctrineSource` annotations.
- Dependencies were passed as `@service_id` strings (e.g., `@router`).
- Relied on annotation reader & reflection.

```php
/**
 * @DG\Datagrid(dependencies={"@router", "@security.csrf.token_manager"})
 * @DG\DoctrineSource(entityClass="App\Entity\FishingGround")
 */
```

---

**New way (PHP 8+):**
- Use native PHP attributes:  
  `#[Datagrid]` and `#[DoctrineSource]`.
- Dependencies are passed using **fully qualified class names** (FQCNs).
- `DoctrineSource` is **injected automatically** based on `entityClass` — **don’t list it** in `dependencies`.

```php
#[DG\Datagrid(dependencies: [RouterInterface::class, CsrfTokenManagerInterface::class])]
#[DG\DoctrineSource(entityClass: FishingMethod::class)]
```

---

### 🔧 Gotchas to watch for:
- Do **not** include `DoctrineSource` in your dependency list — the factory injects it based on the `entityClass`.
- You may need to make some services **public** (e.g. `RouterInterface`) if you're using the container manually via reflection.
    - Solution: mark them as `public: true` in your `services.yaml` or refactor to inject them properly where possible.

---