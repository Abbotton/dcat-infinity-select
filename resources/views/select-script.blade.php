<script require="@select2?lang={{ config('app.locale') === 'en' ? '' : str_replace('_', '-', config('app.locale')) }}" init="{!! $selector !!}">
    var configs = {!! admin_javascript_json($configs) !!};
    var opt = {!! admin_javascript_json($remoteOptions) !!};
    var currentIndex = 0; // 当前操作的select的索引
    var currentValue = "{{$value}}"; // 当前操作的select的值
    var infinityList = [];
    var valueList = "{{$valueList ?? ''}}"; // 编辑时返回的有序链表

    @yield('admin.select-ajax')

    // 获取当前层级长度
    var getInfinitySelectElementLength = function () {
        return $('.{{$name}}-infinity-select-container select').length;
    }

    // 同步请求远程接口
    var getInfinitySelectData = function (obj = {}) {
        var data = {};
        $.ajax({
            url: opt.url,
            dataType: 'json',
            async: false,
            data: obj,
            beforeSend: function () {
                Dcat.loading();
            },
            success: function (result) {
                if (result.length == 0) {
                    Dcat.info('所有数据已加载完毕');
                }
                data = result;
            },
            complete: function () {
                Dcat.loading(false);
            }
        });

        return data;
    };

    // select2初始化
    var initSelect2 = function (html = '', value = '') {
        // 移除多余元素
        $('.{{$name}}-infinity-select-container select').eq(currentIndex).next().nextAll().remove();
        // 填充DOM
        if (html) {
            $('.{{$name}}-infinity-select-container').append(html);
        }
        // 生成select2实例
        var select2Instance = $('.{{$name}}-infinity-select-container select:last').select2(configs);
        // 赋值时触发选择项变动事件
        if (value) {
            select2Instance.val(value).trigger("change")
        }
        // 选择项变动事件绑定
        select2Instance.on('change', infinityElementChange);
        // 取消选择事件绑定
        select2Instance.on('select2:unselect', infinityElementUnselected);
        // 修改样式
        $('.{{$name}}-infinity-select-container .select2-container').css('margin-right', '10px');
        if (infinityList.length > 0) {
            $('input[name="{{$name}}_list"]').val(Array.from(new Set(infinityList)).join(','));
        }
    }

    // 生成DOM
    var getInfinitySelectHtml = function (result) {
        var html = '<select class="form-control" style="width: 200px;" data-index=' + (currentIndex + 1) + '><option value=""></option>';
        result.forEach(function (v, i) {
            html += '<option value="' + v.id + '"'
            // 处理选中状态
            if (currentValue == v.id) {
                html += ' selected="selected"';
            }
            html += '>' + v.text + '</option>'
        });
        html += '</select>';
        currentIndex++;

        return html;
    }

    // 监听选择项变动
    var infinityElementChange = function (e) {
        var val = $(this).val();
        if (val) {
            $('input[name="{{$name}}"]').val(val);
            infinityList[$(this).data('index')] = val;
            var result = getInfinitySelectData({q: val});
            if (result.length > 0) {
                configs.data = result;
                initSelect2(getInfinitySelectHtml(result));
            } else {
                $('input[name="{{$name}}_list"]').val(Array.from(new Set(infinityList)).join(','));
            }
        }
    }

    // 监听取消选择
    var infinityElementUnselected = function (e) {
        var idx = $(this).data('index');
        $('.{{$name}}-infinity-select-container select').eq(idx).next().nextAll().remove();
        $('.select2-container--open').eq(idx).remove();
        if (infinityList.length > 1) {
            infinityList.splice(idx);
            $('input[name="{{$name}}_list"]').val(Array.from(new Set(infinityList)).join(','));
        }
        currentIndex = getInfinitySelectElementLength() - 1;
    }

    // 不论编辑或者创建, 都需要取得顶层的数据.
    var data = getInfinitySelectData();
    // 创建
    if (!valueList) {
        configs.data = data;
        initSelect2();
    } else {
        // 编辑
        var tmpValueList = valueList.split(',');
        if (tmpValueList.length > 0) {
            infinityList = tmpValueList;
            $('.{{$name}}-infinity-select-container select').remove();
            tmpValueList.forEach(function (v, i) {
                currentIndex = i;
                currentValue = v;
                // 因为有序链表的最后一项即为创建时选中的最后一项, 所以这里取得下级数据的时候, 忽略最后一项.
                if (i > 0) {
                    data = getInfinitySelectData({q: tmpValueList[i - 1]});
                }
                configs.data = data;
                initSelect2(getInfinitySelectHtml(data), v);
            });
        }
    }

    {!! $cascadeScript !!}
</script>

