<?php

declare(strict_types=1);

use App\Entity\QueryBuilder\UserQueryBuilder;
use Yiisoft\Html\Html;

/**
 * @var UserQueryBuilder $user
 */
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Delete User</h1>
        <a href="/user" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">Confirm Deletion</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Warning:</strong> This action cannot be undone. Are you sure you want to delete this user?
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">ID:</th>
                            <td><?= $user->getId() ?></td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td><?= Html::encode($user->getName()) ?></td>
                        </tr>
                        <tr>
                            <th>Surname:</th>
                            <td><?= Html::encode($user->getSurname()) ?></td>
                        </tr>
                        <tr>
                            <th>Username:</th>
                            <td><?= Html::encode($user->getUsername() ?? 'N/A') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 120px;">Email:</th>
                            <td><?= Html::encode($user->getEmail()) ?></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?= Html::encode($user->getPhone() ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-<?= $user->getStatus() === 100 ? 'success' : 'secondary' ?>">
                                    <?= $user->getStatus() === 100 ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <form method="post" action="/user/delete/<?= $user->getId() ?>">
                <div class="d-flex justify-content-end">
                    <a href="/user" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
