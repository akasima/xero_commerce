<template>
    <div class="xe-shop category-item-wrap xe-hidden-sm xe-hidden-xs">
        <div class="container">
            <ol class="category-item" :class="{'not-first':!first}">
                <li><a href="#" class="home">HOME</a><i class="xi-angle-right-thin"></i></li>
                <li v-for="(tree, key) in levelTree">
                    <div class="xe-dropdown ">
                        <button class="xe-btn" type="button" data-toggle="xe-dropdown">{{checked[key].label}}</button>
                        <ul class="xe-dropdown-menu">
                            <li v-for="item in tree"><a :href="item.self.url">{{item.self.label}}</a></li>
                        </ul>
                    </div>
                    <i v-if="key<levelTree.length-1" class="xi-angle-right-thin"></i>
                </li>
            </ol>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductCategoryComponent",
        computed: {

        },
        props: [
            'categorys', 'targetCategory', 'first'
        ],
        data () {
            return {
                levelTree:[],
                checked:[]
            }
        },
        methods: {
            recursivePush (categorys, key) {
                var find = categorys.find(v=>{return v.self.id === this.targetCategory[key].id})
                if(find){
                    this.levelTree.push(categorys)
                    this.checked.push(this.targetCategory[key])
                    if(this.targetCategory.length -1 > key){
                        this.recursivePush(find.children, key+1);
                    }
                }
            }
        },
        mounted () {
            this.recursivePush(this.categorys, 0)
            console.log(this.categorys)
            console.log(this.targetCategory)
        }
    }
</script>

<style scoped>
    ol.not-first {
        margin-top: 0;
    }
    ol.not-first a.home{
        opacity: 0;
    }
</style>
