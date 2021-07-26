## Dcat Admin 无限级联动Select组件

### **如果感觉好用，还请给个`Star`鼓励一下，谢谢 :beers: 。**

该插件是 [Dcat Admin](https://learnku.com/docs/dcat-admin/) 的插件，安装方式遵循Dcat Admin官方文档。

## 界面展示

<div align="center">

![image](screenshots/demo.gif)

</div>

## 实现原理

该组件继承自`Dcat\Admin\Form\Field\Select`类，兼容原组件的属性和方法，并新增了一个`list()`方法用来在编辑时设定创建时生成的有序链表。

1. 页面初次渲染成功后，会调用`option()`方法中设定的接口地址，取得顶层数据，并实例化`select2`前端组件；
2. 监听`select2`前端组件的`change`事件，在选择项发生变动时通过向接口传入已选择项的值来获取下级数据，并生成新的DOM；
3. 监听`select2`前端组件的`select2:unselected`事件，在取消选择某一项的时候，修改页面DOM并处理一些其他的业务逻辑；

## 安装

#### composer安装

```shell
composer require abbotton/dcat-infinity-select
```

#### 应用商店安装

``` 
等待Dcat Admin 上商店 
```

## 使用

表单：

```php
// app/Admin/Controllers/ProductController.php

protected function form()
{
    return Form::make(new Product(), function (Form $form) {
        // 创建时.
        $form->infinitySelect('category', '无限联动')->options(url('foo/bar'));
        // 编辑时.
        $form->infinitySelect('category', '无限联动')->options(url('foo/bar'))->list('1,2,6')->value(6);
        // 获取提交的数据.
        $form->saving(function (Form $form) {
            // 获取最终选择的一项
            $category = $form->input('category');
            // 获取整个有序链表, `key`由表单的`name值`拼接`_list`生成.
            $categoryList = $form->input('category_list');
        });
    });
}
```

路由：
```php
Route::get('foo/bar', [\App\Http\Controllers\SomeController::class, 'someMethod']);
```

接口：
```php
public function someMethod(Request $request)
{
    $key = $request->get('q', 0);
    
    return Category::where('pid', $key)->get(['id', DB::raw('name as text')]);
}
```

## License

MIT
