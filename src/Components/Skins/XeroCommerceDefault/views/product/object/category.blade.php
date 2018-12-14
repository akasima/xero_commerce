{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div class="xe-shop category-item-wrap xe-hidden-sm xe-hidden-xs" id="category">
    <div class="container">
        <ol class="category-item" :class="{'not-first':!first}">
            <li><a href="#" class="home">HOME</a><i class="xi-angle-right-thin"></i></li>
            <li v-for="(tree, key) in levelTree">
                <div class="xe-dropdown ">
                    <button class="xe-btn" type="button" data-toggle="xe-dropdown">@{{checked[key].label}}</button>
                    <ul class="xe-dropdown-menu">
                        <li v-for="item in tree"><a :href="item.self.url">@{{item.self.label}}</a></li>
                    </ul>
                </div>
                <i v-if="key<levelTree.length-1" class="xi-angle-right-thin"></i>
            </li>
        </ol>
    </div>
</div>
<script>
    $(function() {
        var qna = new Vue({
            el: "#category",
            name: "ProductCategoryComponent",
            data() {
                return {
                    levelTree: [],
                    checked: [],
                    categorys:{!! json_encode($categorys) !!},
                    targetCategory:{!! json_encode($target) !!},
                    first:{{$first?'true':'false'}}
                }
            },
            methods: {
                recursivePush(categorys, key) {
                    var find = categorys.find(v => {
                        return v.self.id === this.targetCategory[key].id
                    })
                    if (find) {
                        this.levelTree.push(categorys)
                        this.checked.push(this.targetCategory[key])
                        if (this.targetCategory.length - 1 > key) {
                            this.recursivePush(find.children, key + 1);
                        }
                    }
                }
            },
            mounted() {
                this.recursivePush(this.categorys, 0)
            }
        });
    });
</script>
