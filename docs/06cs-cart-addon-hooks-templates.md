# CS-Cartアドオン開発：テンプレートフックとオーバーライド

## テンプレートフックとは

テンプレートフックは、CS-Cartの既存テンプレートを改変・拡張するための仕組みです。コアのテンプレートファイル内に配置された特定のフック（`{hook name="..."}{/hook}`）を利用して、アドオンから機能を追加できます。

### フックの基本概念

コアテンプレート内には以下のようなフック定義が存在します：

```smarty
{* コアテンプレート内のフック定義例 *}
{hook name="products.view"}{/hook}
```

このフックを利用することで、既存の画面に新しい要素を追加したり、既存の要素を変更したりできます。

## テンプレートフックの種類

テンプレートフックには3種類あり、それぞれ異なる目的で使用します：

| フック種類 | 説明 | 使用ケース |
|---------|------|----------|
| `pre` | フック定義の**前**に内容を挿入 | 既存コンテンツの前に情報を追加 |
| `post` | フック定義の**後**に内容を挿入 | 既存コンテンツの後に情報を追加 |
| `override` | フック定義を**完全に置換** | 既存コンテンツを独自の実装で置き換え |

## テンプレートフックの実装方法

### 1. preフック（前方挿入）

```smarty
{* design/themes/{theme}/addons/{addon_id}/hooks/products/view.pre.tpl *}

{** 
 * 商品詳細ページの表示前に実行されるフック
 * 例：商品情報の前に特別なバナーを表示
 *}
<div class="ty-banner">特別セール実施中！</div>
```

### 2. postフック（後方挿入）

```smarty
{* design/themes/{theme}/addons/{addon_id}/hooks/products/view.post.tpl *}

{** 
 * 商品詳細ページの表示後に実行されるフック
 * 例：商品情報の後に関連商品を表示
 *}
<div class="ty-related-products">
    <h3>関連商品</h3>
    {* 関連商品表示ロジック *}
</div>
```

### 3. overrideフック（完全置換）

```smarty
{* design/themes/{theme}/addons/{addon_id}/hooks/products/view.override.tpl *}

{** 
 * 商品詳細ページの表示を完全に置き換えるフック
 * 注意：元の実装は完全に無視される
 *}
{hook name="products.view"}
    <div class="ty-custom-product-view">
        {* 独自の商品表示ロジック *}
        <h1>{$product.product}</h1>
        <div class="price">{include file="common/price.tpl" value=$product.price}</div>
    </div>
{/hook}
```

## ファイル配置規則

テンプレートフックファイルは以下の命名規則に従って配置します：

```
design/themes/{theme}/addons/{addon_id}/hooks/{template_path}/{hook_name}.{hook_type}.tpl
```

- `{theme}` - テーマ名（例：responsive）
- `{addon_id}` - アドオンID
- `{template_path}` - フックが定義されているテンプレートのパス
- `{hook_name}` - フック名
- `{hook_type}` - フックタイプ（pre/post/override）

## テンプレートのオーバーライド

フックでは解決できない場合、テンプレートファイル全体をオーバーライドできます。

### テンプレートオーバーライドの手順

1. オーバーライドしたいコアテンプレートのパスを特定
2. アドオン内に同じ相対パスでファイルを作成

### 例：バックエンドログインフォームのオーバーライド

**オリジナルファイル**:  
`design/backend/templates/views/auth/login_form.tpl`

**オーバーライドファイル**:  
`app/addons/{addon_id}/backend/templates/addons/{addon_id}/overrides/views/auth/login_form.tpl`

### フロントエンドテンプレートのオーバーライド

フロントエンドテンプレートも同様の方法でオーバーライドできます：

**オリジナルファイル**:  
`design/themes/{theme}/templates/blocks/product_list_templates/default_template.tpl`

**オーバーライドファイル**:  
`design/themes/{theme}/addons/{addon_id}/overrides/blocks/product_list_templates/default_template.tpl`

## ベストプラクティス

- 可能な限りオーバーライドよりもフックを使用する
- オーバーライドする場合は必要最小限の変更にとどめる
- コアの更新に備えて、変更点をドキュメント化する
- テンプレート変数の存在確認を行う（`{if isset($variable)}`）

## まとめ

テンプレートフックとオーバーライドを適切に使い分けることで、CS-Cartの見た目や機能を柔軟にカスタマイズできます。フックはピンポイントの変更に、オーバーライドは大規模な変更に適しています。