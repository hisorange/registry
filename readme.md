## hisorange\Registry
---
PHP 5.3+ or 5.4?

General introduction.

## Installation

## Implementations

## How to use

## Contributions

## TODO
+ Yaml import / export.
+ INI import / export.
+ XML import / export.
+ Array flattening support on the manager.
+ Check if a nesting is a big deal or not, like we can replace an entity with a manager and its nested, so the dotted access is not impossible.
+ Sync with a persistent storage, JSON, SQL, INI, XML something like that allow live sync.
+ Multiple persister if not a big problem.
+ Registry::setPersister(new JsonPersister('data/registry.json'));
+ Implement collection like functions, count, seek, last, first, etc.
+ only, except functions on the manager.