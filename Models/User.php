<?php

namespace App\Models;

class User {
    public const DB_PATH = __DIR__ . '/../db.php';

    public function __construct(
        public string $login,
        public string $firstname,
        public string $lastname,
        public int $age,
        public string $password,
    ) {
    }

    public static function empty(): self {
        return new self(
            login: '',
            firstname: '',
            lastname: '',
            age: 0,
            password: '',
        );
    }

    public static function create(array $data): self {
        $model = static::empty();
        $model->load($data);
        return $model;
    }

    public function load(array $data): void {
        $this->login = (string)($data['login'] ?? $this->login);
        $this->firstname = (string)($data['firstname'] ?? $this->firstname);
        $this->lastname = (string)($data['lastname'] ?? $this->lastname);
        $this->age = (int)($data['age'] ?? $this->age);
        $this->password = (string)($data['password'] ?? $this->password);
    }

    /** @return self[] */
    public static function all(): array {
        $db = static::db();
        $records = $db['users'] ?? [];
        $result = [];

        foreach ($records as $login => $data) {
            $data['login'] = $login;
            $result[$login] = User::create($data);
        }

        return $result;
    }

    public static function find(string $login): ?self {
        return self::all()[$login] ?? null;
    }

    public function save(): void {
        $this->requireLogin();
        $users = self::all();
        $users[$this->login] = $this;
        $this->store($users);
    }

    public function remove(): void {
        $this->requireLogin();
        $users = self::all();
        unset($users[$this->login]);
        $this->store($users);
    }

    public function toArray(): array {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'age' => $this->age,
            'password' => $this->password,
        ];
    }

    public function validate(array $fields): array {
        $errors = [];

        if (in_array('login', $fields)) {
            if (!$this->login) {
                $errors['login'] = 'Пустое значение';
            } elseif (strlen($this->login) < 3) {
                $errors['login'] = 'Длина меньше 3 символов';
            }
        }

        if (in_array('firstname', $fields)) {
            if (!$this->firstname) {
                $errors['firstname'] = 'Пустое значение';
            }
        }

        if (in_array('lastname', $fields)) {
            if (!$this->lastname) {
                $errors['lastname'] = 'Пустое значение';
            }
        }

        if (in_array('age', $fields)) {
            if ($this->age < 0) {
                $errors['age'] = 'Отрицательное значение';
            }
        }

        if (in_array('password', $fields)) {
            if (!$this->password) {
                $errors['password'] = 'Пустое значение';
            } elseif (strlen($this->password) < 4) {
                $errors['password'] = 'Длина меньше 4 символов';
            }
        }

        return $errors;
    }

    /** @param self[] $users */
    private function store(array $users): void {
        $db = static::db();
        $db['users'] = [];

        foreach ($users as $model) {
            if ($model->login) {
                $db['users'][$model->login] = $model->toArray();
            }
        }

        file_put_contents(self::DB_PATH, '<?php return ' . var_export($db, true) . ';' . PHP_EOL);
    }

    private function requireLogin() {
        if (!$this->login) {
            throw new \LogicException('Login is empty');
        }
    }

    private static function db(): array {
        return is_file(self::DB_PATH) ? include self::DB_PATH : [];
    }
}
