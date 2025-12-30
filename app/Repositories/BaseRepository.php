<?php

/**
 * Planning
 *
 * - Provides a reusable base repository for common Eloquent CRUD operations.
 * - Implements `BaseInterface` for consistency across repositories.
 * - Centralizes error handling with logging and custom `RepositoryException`.
 * - Methods: `all`, `findById`, `findByName`, `create`, with placeholders for `update` and `delete`.
 * - Ensures database access layer is decoupled from controllers/services.
 * - Future Planning:
 *   - Implement soft delete support.
 *   - Add update and delete with transaction rollback support.
 */

 namespace App\Repositories;

use App\Contracts\BaseInterface;
 use App\Exceptions\RepositoryException;
 use Illuminate\Database\Eloquent\Collection;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use Illuminate\Database\Eloquent\SoftDeletes;
 
 abstract class BaseRepository implements BaseInterface
 {
     protected Model $model;
 
     public function __construct(Model $model)
     {
         $this->model = $model;
     }
 
     public function all(): Collection
     {
         return $this->execute(
             fn () => $this->model->all(),
             'fetch all records'
         );
     }
 
     public function findById(int $id): ?Model
     {
         return $this->execute(
             fn () => $this->model->find($id),
             "fetch record with ID {$id}"
         );
     }
 
     public function create(array $data): Model
     {
         return $this->execute(
             fn () => DB::transaction(fn () => $this->model->create($data)),
             'create record'
         );
     }
 
     public function update(int $id, array $data): Model
     {
         return $this->execute(
             function () use ($id, $data) {
                 $record = $this->model->findOrFail($id);
                 $record->update($data);
                 return $record;
             },
             "update record with ID {$id}"
         );
     }
 
     public function delete(int $id): bool
     {
         return $this->execute(
             fn () => $this->model->findOrFail($id)->delete(),
             "delete record with ID {$id}"
         );
     }

     public function restore(int $id): Model
    {
        return $this->execute(
            fn () => $this->model->withTrashed()->findOrFail($id)->restore(),
            "restore record with ID {$id}"
        );
    }

    public function forceDelete(int $id): bool
    {
        return $this->execute(
            fn () => $this->model->withTrashed()->findOrFail($id)->forceDelete(),
            "force delete record with ID {$id}"
        );
    }
 
     /**
      * Centralized error handling.
      */
     protected function execute(callable $callback, string $action)
     {
         try {
             return $callback();
         } catch (\Throwable $e) {
             Log::error("Repository failed to {$action}", [
                 'model' => get_class($this->model),
                 'exception' => $e,
             ]);
 
             throw new RepositoryException(
                 "Unable to {$action}",
                 500,
                 $e
             );
         }
     }
 }
