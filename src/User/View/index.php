<?php

declare(strict_types=1);

use Yiisoft\Db\Query\DataReaderInterface;
use Yiisoft\Html\Html;
use Yiisoft\Html\NoEncode;
use Yiisoft\Yii\DataView\Filter\Widget\TextInputFilter;
use Yiisoft\Yii\DataView\GridView\Column\ActionButton;
use Yiisoft\Yii\DataView\GridView\Column\ActionColumn;
use Yiisoft\Yii\DataView\GridView\Column\Base\DataContext;
use Yiisoft\Yii\DataView\GridView\Column\DataColumn;
use Yiisoft\Yii\DataView\GridView\GridView;
use Yiisoft\Yii\DataView\Url\UrlParameterProviderInterface;
use Yiisoft\Yii\DataView\Url\UrlParameterType;
use Yiisoft\Yii\DataView\YiiRouter\UrlCreator;

/**
 * @var DataReaderInterface $dataProvider
 * @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator
 */

$gridViewLayout = <<<HTML
{header}\n
{toolbar}\n
{items}\n
<div class="row">
<div class="col-2">{summary}</div>
<div class="col p-0">{pager}</div>
<div class="col-3 pe-1 text-end">{pageSize}</div>
</div>
HTML;

?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Users</h1>
        <a href="/user/create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create User
        </a>
    </div>

    <style>
        .pagination {
            justify-content: center !important;
        }
    </style>

    <?= GridView::widget()
        ->dataReader($dataProvider)
        ->tableAttributes(['class' => 'table table-striped table-hover'])
        ->urlCreator(new UrlCreator($urlGenerator))
        ->urlParameterProvider(
        // I believe I am missing something and this is just my primitive workaround
        // But method getQueryValue() in class Yiisoft\Yii\DataView\GridView\Column\Base\FilterContext cannot access GET parameters
        // ... so filter values are only in the URL, not in the filter-inputs. See renderFilter() in DataColumnRenderer
            new class implements UrlParameterProviderInterface {
                public function get(string $name, UrlParameterType $type): ?string
                {
                    return $_GET[$name] ?? null;
                }
            }
        )
        ->pageSizeConstraint(3)
        ->layout($gridViewLayout)
        ->sortableLinkAttributes(['style' => 'text-decoration:none; color:inherit;'])
        ->sortableHeaderAscAppend('<span class="fw-bold">⭡</span>')
        ->sortableHeaderAscPrepend('')
        ->sortableHeaderDescAppend('<span class="fw-bold">⭣</span>')
        ->sortableHeaderDescPrepend('')
        ->sortableHeaderPrepend('')
        ->sortableHeaderAppend('<span class="text-secondary text-opacity-50">⭥</span>')
        ->columns(
            new DataColumn(property: 'id', header: 'ID', headerAttributes: ['style' => 'width:4.2rem;'],
                filter: TextInputFilter::widget()->attributes([
                    'style' => 'width:100px',
                    'class' => 'form-control text-center',
                    'value' => 444,
                ])
            ),
            new DataColumn(property: 'name', header: 'Name'),
            new DataColumn(property: 'surname', header: 'Surname'),
            new DataColumn(property: 'username', header: 'Username'),
            new DataColumn(property: 'email', header: 'Email'),
            new DataColumn(property: 'phone', header: 'Phone'),
            new DataColumn(property: 'status', header: 'Status'),
            new ActionColumn(
                urlCreator: static function (string $action, DataContext $context) use ($urlGenerator): string {
                    // the keyword "static" prevents PHP from binding $this = saves time in large grids
                    // $action = the key in the array of buttons
                    return $urlGenerator->generate("user/$action", ['id' => $context->data['id']]);
                },
                header: 'Actions',
                buttons: [
                    'view' => new ActionButton(NoEncode::string('<i class="fa-solid fa-eye"></i>'), attributes: ['title' => 'View']),
                    'update' => new ActionButton(NoEncode::string('<i class="fa-solid fa-pencil"></i>'), attributes: ['title' => 'View']),
                    'delete' => new ActionButton(NoEncode::string('<i class="fa-solid fa-trash"></i>'), attributes: ['title' => 'View', 'onclick' => 'return confirm("Are you sure you want to delete this user?")']),
                ],
                headerAttributes: ['style' => 'width:11rem'],
            ),
            new ActionColumn(
                header: 'Actions',
                content: static function ($data, DataContext $context) use ($urlGenerator): string {
                    $eye = '<i class="fa-regular fa-eye"></i>';
                    $pencil = '<i class="fa-solid fa-pencil"></i>';
                    $trash = '<i class="fa-solid fa-trash"></i>';
                    $viewIcon = (string)Html::a($eye,
                        $urlGenerator->generate('user/view', ['id' => $data['id']]),
                    )->encode(false);
                    $updateIcon = (string)Html::a($pencil,
                        $urlGenerator->generate('user/update', ['id' => $data['id']]),
                    )->encode(false);
                    $deleteIcon = (string)Html::a($trash,
                        $urlGenerator->generate('user/delete', ['id' => $data['id']]),
                        ['onclick' => 'return confirm("Are you sure you want to delete this user?")']
                    )->encode(false);
                    return $viewIcon . $updateIcon . $deleteIcon;
                },
                headerAttributes: ['style' => 'width:5rem'],
            )
        )
    ?>
</div>
