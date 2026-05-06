<?php

declare(strict_types=1);

use Yiisoft\Db\Query\DataReaderInterface;
use Yiisoft\Html\Html;
use Yiisoft\Yii\DataView\Filter\Widget\TextInputFilter;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\Base\DataContext;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Yii\DataView\YiiRouter\UrlCreator;

/**
 * @var DataReaderInterface $dataProvider
 * @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 */

?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Users</h1>
        <a href="/user/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create User
        </a>
    </div>

    <?= GridView::widget()
        ->dataReader($dataProvider)
        ->tableAttributes(['class' => 'table table-striped table-hover'])
        ->urlCreator(new UrlCreator($urlGenerator))
        ->pageSizeConstraint(3)
        ->sortableLinkAttributes(['style' => 'text-decoration:none; color:inherit;'])
        ->sortableHeaderAscAppend('<span class="fw-bold">⭡</span>')
        ->sortableHeaderAscPrepend('')
        ->sortableHeaderDescAppend('<span class="fw-bold">⭣</span>')
        ->sortableHeaderDescPrepend('')
        ->sortableHeaderPrepend('')
        ->sortableHeaderAppend('<span class="text-secondary text-opacity-50">⭥</span>')
        ->columns(
            new DataColumn(property: 'id', header: 'ID', headerAttributes: ['style' => 'width:4.2rem;'],
                filter: TextInputFilter::widget()->attributes(['style' => 'width:100px', 'class' => 'form-control text-center']),
            ),
            new DataColumn(property: 'name', header: 'Name'),
            new DataColumn(property: 'surname', header: 'Surname'),
            new DataColumn(property: 'username', header: 'Username'),
            new DataColumn(property: 'email', header: 'Email'),
            new DataColumn(property: 'phone', header: 'Phone'),
            new DataColumn(property: 'status', header: 'Status'),
            new ActionColumn(
                urlCreator: function ($action, DataContext $context) {
                    return "/user/$action/" . $context->data['id'];
                },
                content: static function ($data, DataContext $context): string {
                    $viewIcon = (string)Html::a(
                        '<i class="fa-regular fa-eye"></i>',
                        '/user/view/' . $data['id'],
                        ['encode' => false]
                    )->encode(false);
                    $editIcon = (string)Html::a(
                        '<i class="fa-solid fa-pencil"></i>',
                        '/user/edit/' . $data['id'],
                        ['encode' => false]
                    )->encode(false);
                    $deleteIcon = (string)Html::a(
                        '<i class="fa-solid fa-trash"></i>',
                        '/user/delete/' . $data['id'],
                        ['encode' => false]
                    )->encode(false);
                    return $viewIcon . $editIcon . $deleteIcon;
                },
                headerAttributes: ['style' => 'width:6rem'],
            )
        )
    ?>
</div>
