<?php

declare(strict_types=1);

use App\Entity\QueryBuilder\UserQueryBuilder;
use App\User\Form\UserUpdateForm;
use Yiisoft\FormModel\Field;
use Yiisoft\Html\Html;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\Renderer\Csrf;

/**
 * @var UserQueryBuilder $user
 * @var UserUpdateForm $form
 * @var array $validationErrors
 * @var UrlGeneratorInterface $urlGenerator
 * @var Csrf $csrf
 */

$htmlForm = Html::form()
    ->post($urlGenerator->generate('user/update', ['id' => $user->getId()]))
    ->csrf($csrf);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Update User</h1>
        <a href="/user" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update User Information</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($validationErrors)): ?>
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0">
                        <?php foreach ($validationErrors as $error): ?>
                            <li><?= Html::encode($error->getMessage()) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?= $htmlForm->open() ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <?= Field::text($form, 'name')
                            ->label('Name')
                            ->addInputClass('form-control')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                            ->addInputAttributes(['required' => true])
                        ?>
                    </div>
                    <div class="mb-3">
                        <?= Field::text($form, 'surname')
                            ->label('Surname')
                            ->addInputClass('form-control')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                            ->addInputAttributes(['required' => true])
                        ?>
                    </div>
                    <div class="mb-3">
                        <?= Field::text($form, 'username')
                            ->label('Username')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                            ->addInputClass('form-control')
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <?= Field::email($form, 'email')
                            ->label('Email')
                            ->addInputClass('form-control')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                            ->addInputAttributes(['required' => true])
                        ?>
                    </div>
                    <div class="mb-3">
                        <?= Field::telephone($form, 'phone')
                            ->label('Phone')
                            ->addInputClass('form-control')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                        ?>
                    </div>
                    <div class="mb-3">
                        <?= Field::select($form, 'status')
                            ->optionsData([
                                0 => 'Active',
                                100 => 'Inactive',
                            ])
                            ->label('Status')
                            ->addInputClass('form-select')
                            ->inputInvalidClass('is-invalid')
                            ->errorAttributes(['class' => 'invalid-feedback'])
                        ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <?= Html::a('Cancel', '/user', ['class' => 'btn btn-secondary me-2']) ?>
                <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary']) ?>
            </div>

            <?= $htmlForm->close() ?>
        </div>
    </div>
</div>
