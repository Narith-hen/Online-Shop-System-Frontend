# Online Shop System - Database Schema ERD

## Entity Relationship Diagram

```
┌──────────────────────┐       ┌──────────────────────┐
│       roles          │       │     role_user        │
├──────────────────────┤       ├──────────────────────┤
│ id (PK)              │──┐    │ user_id (PK, FK)     │
│ name (UNQ)           │  │    │ role_id (PK, FK)     │──┐
│ description (NULL)   │  │    │ created_at           │  │
│ created_at           │  │    │ updated_at           │  │
│ updated_at           │  │    └──────────────────────┘  │
└──────────────────────┘  │                              │
                          │    ┌──────────────────────────┘
                          │    │
┌──────────────────────────┐   │
│         users            │   │
├──────────────────────────┤   │
│ id (PK)                  │   │
│ code (UNQ, NULL)         │   │
│ name                     │   │
│ avatar (NULL)            │   │
│ email (UNQ)              │   │
│ email_verified_at (NULL) │   │
│ password                 │   │
│ provider (NULL)          │   │
│ provider_id (NULL)       │   │
│ provider_avatar (NULL)   │   │
│ role_id (FK, NULL) ──────│───┘
│ remember_token           │
│ created_at               │
│ updated_at               │
└──────────────────────────┘
         │
         │ 1 ──── M
         │
    ┌────┴──────────┬──────────────┬──────────────┐
    │               │              │              │
    ▼               ▼              ▼              ▼
┌───────┐    ┌──────────┐   ┌──────────┐   ┌──────────────┐
│orders │    │cart_items│   │wishlist  │   │   reviews    │
└───────┘    └──────────┘   │  _items  │   └──────────────┘
    │         │              └──────────┘         │
    │         │                                   │
    │         │  M ──────── 1                     │
    │         │                                   │
    │    ┌────┴────┬────────────┘                 │
    │    │         │                              │
    │    │         ▼                              │
    │    │  ┌────────────────┐                    │
    │    │  │   products     │◄───────────────────┘
    │    │  └────────────────┘
    │    │    │
    │    │    │ M ──── 1
    │    │    │
    │    │    ▼
    │    │  ┌────────────────┐
    │    │  │  categories    │
    │    │  ├────────────────┤
    │    │  │ id (PK)        │──┐
    │    │  │ parent_id (FK) │◄─┘ (self-ref)
    │    │  │ name           │
    │    │  │ description    │
    │    │  │ image (NULL)   │
    │    │  │ is_active      │
    │    │  │ created_at     │
    │    │  │ updated_at     │
    │    │  └────────────────┘
    │    │
    │    ▼
    │  ┌──────────────┐
    │  │ order_items  │
    │  ├──────────────┤
    │  │ id (PK)      │
    │  │ order_id(FK) │
    │  │ product_id   │──► products
    │  │ quantity     │
    │  │ price        │
    │  │ created_at   │
    │  │ updated_at   │
    │  └──────────────┘
    │
```

## Tables Detail

### users
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | User ID |
| code | VARCHAR | UNIQUE, NULLABLE | User code (e.g. SPU001) |
| name | VARCHAR | NOT NULL | Full name |
| avatar | VARCHAR | NULLABLE | Avatar image path |
| email | VARCHAR | UNIQUE, NOT NULL | Email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification time |
| password | VARCHAR | NOT NULL | Hashed password |
| provider | VARCHAR | NULLABLE | OAuth provider (google/github) |
| provider_id | VARCHAR | NULLABLE | OAuth provider user ID |
| provider_avatar | VARCHAR | NULLABLE | OAuth avatar URL |
| role_id | BIGINT UNSIGNED | FK->roles, NULLABLE | User role |
| remember_token | VARCHAR | NULLABLE | Remember me token |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique Indexes:** `(provider, provider_id)`

### roles
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Role ID |
| name | VARCHAR | UNIQUE, NOT NULL | Role name (admin/customer) |
| description | TEXT | NULLABLE | Role description |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

### categories
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Category ID |
| parent_id | BIGINT UNSIGNED | FK->categories(id), SET NULL | Parent category |
| name | VARCHAR | NOT NULL | Category name |
| description | TEXT | NULLABLE | Category description |
| image | VARCHAR | NULLABLE | Category image path |
| is_active | BOOLEAN | DEFAULT true | Active status |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

### products
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Product ID |
| name | VARCHAR | NOT NULL | Product name |
| image | VARCHAR | NULLABLE | Product image path |
| price | DECIMAL(8,2) | NOT NULL | Product price |
| stock | INTEGER | NOT NULL | Available stock |
| is_active | BOOLEAN | DEFAULT true | Active status |
| category_id | BIGINT UNSIGNED | FK->categories, CASCADE | Category |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

### orders
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Order ID |
| user_id | BIGINT UNSIGNED | FK->users | Customer |
| status | VARCHAR | DEFAULT 'pending' | pending/processing/shipped/delivered/cancelled |
| total | DECIMAL(10,2) | NOT NULL | Order total |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

### order_items
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Item ID |
| order_id | BIGINT UNSIGNED | FK->orders | Parent order |
| product_id | BIGINT UNSIGNED | FK->products | Product |
| quantity | INTEGER | NOT NULL | Quantity ordered |
| price | DECIMAL | NOT NULL | Price at time of order |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

### cart_items
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Cart item ID |
| user_id | BIGINT UNSIGNED | FK->users | User |
| product_id | BIGINT UNSIGNED | FK->products | Product |
| quantity | INTEGER | NOT NULL | Quantity |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique Index:** `(user_id, product_id)`

### wishlist_items
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Wishlist item ID |
| user_id | BIGINT UNSIGNED | FK->users | User |
| product_id | BIGINT UNSIGNED | FK->products | Product |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique Index:** `(user_id, product_id)`

### reviews
| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Review ID |
| user_id | BIGINT UNSIGNED | FK->users | Reviewer |
| product_id | BIGINT UNSIGNED | FK->products | Product |
| rating | INTEGER | NOT NULL, 1-5 | Rating score |
| comment | TEXT | NULLABLE | Review comment |
| created_at | TIMESTAMP | | |
| updated_at | TIMESTAMP | | |

**Unique Index:** `(user_id, product_id)` - One review per user per product

## Relationships

| From | To | Type | Notes |
|------|----|------|-------|
| users.role_id | roles.id | M:1 | Each user has one role |
| categories.parent_id | categories.id | M:1 (self) | Self-referencing hierarchy |
| products.category_id | categories.id | M:1 | Each product belongs to one category |
| orders.user_id | users.id | M:1 | Each order belongs to one user |
| order_items.order_id | orders.id | M:1 | Each item belongs to one order |
| order_items.product_id | products.id | M:1 | Each item references one product |
| cart_items.user_id | users.id | M:1 | Each cart item belongs to one user |
| cart_items.product_id | products.id | M:1 | Each cart item references one product |
| wishlist_items.user_id | users.id | M:1 | Each wishlist item belongs to one user |
| wishlist_items.product_id | products.id | M:1 | Each wishlist item references one product |
| reviews.user_id | users.id | M:1 | Each review is by one user |
| reviews.product_id | products.id | M:1 | Each review is for one product |

## Business Rules

- **Cart:** One cart item per user per product (unique constraint). Adding same product increments quantity.
- **Wishlist:** One wishlist entry per user per product. Toggle support for add/remove.
- **Reviews:** One review per user per product. Rating must be between 1-5.
- **Orders:** Cart is cleared upon successful checkout. Product stock is decremented.
- **Checkout:** 10% tax applied automatically. Products must be active and in stock.
- **Order Cancellation:** Only orders with 'pending' status can be cancelled.
