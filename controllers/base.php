<?php

trait CrudTrait
{
    protected $pdo;
    protected $table;

    public function initialize(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function read(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function readAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool
    {
        $setClause = implode(", ", array_map(function ($column) {
            return "{$column} = :{$column}";
        }, array_keys($data)));

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}


class VoitureCRUD
{
    use CrudTrait;

    public function __construct(PDO $pdo)
    {
        $this->initialize($pdo, "voitures");
    }

    public function deleteByNumImmatriculation(string $NumImmatriculation): bool
    {
        if (empty($NumImmatriculation)) {
            throw new InvalidArgumentException("Numéro d'immatriculation manquant.");
        }

        $sql = "DELETE FROM {$this->table} WHERE NumImmatriculation = :NumImmatriculation";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['NumImmatriculation' => $NumImmatriculation]);
    }

    public function getByNumImmatriculation(string $NumImmatriculation): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE NumImmatriculation = :NumImmatriculation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['NumImmatriculation' => $NumImmatriculation]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}

class ContratCRUD
{
    use CrudTrait;

    public function __construct(PDO $pdo)
    {
        $this->initialize($pdo, "contrats");
    }
}

class ClientCRUD
{
    use CrudTrait;

    public function __construct(PDO $pdo)
    {
        $this->initialize($pdo, "clients");
    }
}


?>