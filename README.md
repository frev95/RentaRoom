# RentaRoom

## Description

This project is a platform for booking rooms by companies or associations.
This is an "ECF Back" project.

The technologies used are:
- Symfony
- Twig
- SQLite

## Entities

### User

This entity represents a user of the platform; the user is supposed to be a company or an association.

| Property     | Type      | Description          | Relation    |
| ------------ | --------- | -------------------- | ----------- |
| email        | string    | 180 NOT NULL, UNIQUE |             |
| password     | string    | 255 NOT NULL         |             |
| name         | string    | 80                   |             |
| phone        | string    | 30                   |             |
| role         | string    | 30 NOT NULL          |             |
| reservations | OneToMany |                      | Reservation |

The property `role` is useless (was confused with user/admin roles).

---

### Room

This entity represents a room to be rented.

| Property     | Type       | Description          | Relation    |
| ------------ | ---------- | -------------------- | ----------- |
| name         | string     | 80 NOT NULL          |             |
| description  | text       | NOT NULL             |             |
| capacity     | integer    | NOT NULL             |             |
| category     | ManyToOne  | NULL                 | Category    |
| images       | OneToMany  |                      | Booking     |
| features     | ManyToMany |                      | Feature     |
| reservations | OneToMany  |                      | Reservation |

---

### Reservation

This entity represents a reservation by an user for a room.

| Property   | Type      | Description          | Relation |
| ---------- | --------- | -------------------- | -------- |
| start_date | timedate  | NOT NULL             |          |
| end_date   | timedate  | NOT NULL             |          |
| status     | integer   | NOT NULL             |          |
| user       | ManyToOne | NOT NULL, OrphanTrue | User     |
| room       | ManyToOne | NOT NULL, OrphanTrue | Room     |

---

### Image

This entity represents an image of a room.

| Property   | Type      | Description          | Relation |
| ---------- | --------- | -------------------- | -------- |
| url        | string    | 400 NOT NULL         |          |
| room       | ManyToOne | NOT NULL, OrphanTrue | Room     |

---

### Feature

This entity represents a feature (typed as equipment, ergonomy, software...) for a room.

| Property    | Type       | Description | Relation |
| ----------- | ---------- | ----------- | -------- |
| name        | string     | 80 NOT NULL |          |
| description | text       | NULL        |          |
| type        | ManyToOne  |             | Type     |
| rooms       | ManyToMany |             | Room     |

---

### Category

This entity represents the category of a room.

| Property    | Type      | Description | Relation |
| ----------- | --------  | ----------- | -------- |
| name        | string    | 40 NOT NULL |          |
| rooms       | oneToMany |             | Room     |

---

### Type

This entity represents the type of a feature for a room.

| Property    | Type      | Description | Relation |
| ----------- | --------  | ----------- | -------- |
| name        | string    | 40 NOT NULL |          |
| features    | oneToMany |             | Feature  |

---

## Pages architecture

-- all rooms
    -- room
        -- reservation (if logged)
-- login
-- register
-- account
    -- user reservations
        -- add, modify or cancel a reservation
    -- admin dashboard
        -- reservations to be validated or rejected
        -- create, edit or delete a room with images and features
        -- create, edit or delete a feature
        -- delete an user