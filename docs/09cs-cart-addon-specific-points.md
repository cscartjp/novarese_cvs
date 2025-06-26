# CS-Cart固有の開発ポイント

## 6.1 アドオン間の依存関係設定
```xml
<depends>
    <item>another_addon</item>
</depends>
```

## 6.2 アドオン設定項目の追加
```xml
<settings>
    <sections>
        <section id="general">
            <items>
                <item id="example_setting">
                    <type>input</type>
                    <default_value>デフォルト値</default_value>
                </item>
            </items>
        </section>
    </sections>
</settings>
```

## 6.3 管理画面メニュー項目の追加
```php
// app/addons/{addon_id}/schemas/menu/menu.post.php

$schema['central']['addons']['items']['{addon_id}'] = [
    'attrs' => [
        'class' => 'is-addon'
    ],
    'href' => 'example.manage',
    'position' => 100,
    'subitems' => [
        'manage_items' => [
            'href' => 'example.manage',
            'position' => 10
        ]
    ]
];

return $schema;
```

## 6.4 フックの登録
```php
// app/addons/{addon_id}/schemas/routes/routes.post.php

// フロントエンド用ルート
$schema['example'] = [
    'dispatch' => 'example'
];

return $schema;
```