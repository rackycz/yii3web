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
        <h1>User Details</h1>
        <div>
            <a href="/user/edit/<?= $user->getId() ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="/user/delete/<?= $user->getId() ?>" class="btn btn-danger">
                <i class="bi bi-trash"></i> Delete
            </a>
            <a href="/user" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">User Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">ID:</th>
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
                            <th style="width: 150px;">Email:</th>
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
                        <tr>
                            <th>Email Verified:</th>
                            <td>
                                <?= $user->getEmailVerifiedAt()
                                    ? '<span class="text-success">Yes</span>'
                                    : '<span class="text-danger">No</span>' ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
