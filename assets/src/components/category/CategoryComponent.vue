<template>
    <div>
        <input type="hidden" name="newCategory" v-model="categoryString">
        <div v-if="!loading" v-for="(component, index) in createComponents" style="display: flex">
            <div style="flex-grow: 10">
                <select class="form-control components" :id="'select'+index" v-model="component.category_id" @change="updateChild(component.category_id, index)">
                    <option>선택</option>
                    <option :value="item.value" v-for="item in componentCategoryOptions[index]">{{item.text}}</option>
                </select>
                <select v-if="captureLength[index] > 0" class="form-control components" :id="'select'+index" v-model="component.category_id" @change="changeOptions(component.category_id,index)">
                    <option>선택</option>
                    <option  v-for="item in childItems[index]" :value="item.value">{{item.text}}</option>
                </select>
            </div>
            <div style="flex-grow: 1">
                <button v-on:click="removeCreateComponent(index)" type="button" class="xe-btn xe-btn-danger xe-btn-block"
                >삭제
                </button>
            </div>
        </div>
        <div v-if="loading">
            카테고리 로드중...
        </div>
        <button class="xe-btn xe-btn-block" type="button" @click="addCreateComponent">추가</button>
    </div>
</template>

<script>
    export default {
        name: "CategoryComponent",
        props: ['categoryItems', 'mode', 'getChildUrl', 'selected'],
        computed: {
            categoryString () {
                return this.createComponents.map(function(v){return v.category_id}).join(',')
            },
            captureLength () {
                return this.childItems.map(function(v){return v.length});
            }
        },
        data() {
            return {
                createComponents: [],
                childItems: [],
                componentCategoryOptions: [],
                firstCategoryId: (this.categoryItems.length===0) ? '' : this.categoryItems[0].value,
                loading: false
            }
        },
        mounted() {
            if (this.selected) {
                console.log(this.selected)
                $.each(this.selected, (k,v)=>{
                    var find = this.find(this.categoryItems, v)
                    if(!find){
                        this.loading = true
                        $.each(this.categoryItems, (key,value)=>{
                            $.ajax({
                                url: this.getChildUrl,
                                type: 'get',
                                dataType: 'json',
                                data: {'parentId': value},
                                success: data => {
                                    var childCategories = data.categories;
                                    var childFind = this.find(childCategories, v)
                                    if(childFind){
                                        var temp_array = this.componentCategoryOptions.slice()
                                        temp_array[k]=childCategories
                                        this.componentCategoryOptions = temp_array
                                        this.loading = false
                                    }
                                }
                            });
                        })
                    }
                    this.createComponents.push({
                        category_id: v
                    });
                    this.childItems.push([]);
                    this.componentCategoryOptions.push (this.categoryItems)
                    this.updateChild(v, k)
                })
            } else {
                this.addCreateComponent()
            }
        },
        methods: {
            addCreateComponent: function () {
                this.createComponents.push({
                    category_id: this.firstCategoryId
                });
                this.childItems.push([]);
                this.componentCategoryOptions.push(this.categoryItems)
            },
            removeCreateComponent: function (componentIndex) {
                this.createComponents.splice(componentIndex, 1)
                this.childItems.splice(componentIndex, 1)
                this.componentCategoryOptions.splice(componentIndex, 1)

                if (this.createComponents.length === 0) {
                    alert('최소 하나의 카테고리가 필요합니다.')
                    this.addCreateComponent()
                }
            },
            updateChild(target, index){
                var temp_array = this.childItems.slice()
                $.ajax({
                    url: this.getChildUrl,
                    type: 'get',
                    dataType: 'json',
                    data: {'parentId': target},
                    success: data => {
                        var childCategories = data.categories;
                        temp_array[index]=childCategories
                        this.childItems = temp_array
                    }
                });
            },
            changeOptions(target,index)
            {
                this.componentCategoryOptions[index] = this.childItems[index]
                this.updateChild(target,index)
            },
            find(list,value)
            {
                var find = list.find(item=>{
                    return item.value === value
                })
                return typeof find !=='undefined'
            }
        }
    }
</script>

<style scoped>

</style>
