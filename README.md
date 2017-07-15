# Akuma Database Management Tool

### Limitations
- Supports only Symfony 2/3 projects

### Installation

```
    composer global require akuma/db-manager-tool:dev-master
```

### CLI Commands

- `akuma-dbm snapshot:dump` - creates database snapshot for given connection with optional suffix
    - `connection` is connection name to be dumped (OPTIONAL, Default: null)
    - `id` is suffix for dump name (be used for restore command) (OPTIONAL, Default: current date) 
- `akuma-dbm snapshot:restore` - restores database snapshot for given connection with optional suffix
    - `connection` is connection name to be dumped (OPTIONAL, Default: null)
    - `id` is suffix for dump name (be used for restore command) (OPTIONAL, Default: current date)
