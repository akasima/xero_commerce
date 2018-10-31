<template>
    <div>
        <input type="hidden" name="newCategory" v-model="categoryString">

        <create-category-component v-for="(component, componentIndex) in this.createComponents"
                                   v-on:selectCategoryItem="selectCategoryItem"
                                   v-on:addCreateComponent="addCreateComponent"
                                   v-on:removeCreateComponent="removeCreateComponent"
                                   :category-items="categoryItems"
                                   :get-child-url="getChildUrl"
                                   :componentIndex="componentIndex"
                                   :key="componentIndex"
                                   :selected="component">
        </create-category-component>
    </div>
</template>

<script>
    export default {
        name: "CategoryComponent",
        props: ['categoryItems', 'mode', 'getChildUrl', 'selected'],
        data() {
            return {
                createComponents: [],
                newCategoryItems: [],
                categoryString: this.selected ? this.selected : '',
            }
        },
        mounted() {
            if (this.selected) {
                console.log(this.selected)
                $.each(this.selected, (k,v)=>{
                    this.createComponents.push(v);
                    this.newCategoryItems.push(v)
                })
            } else {
                this.createComponents.push('');
            }

        },
        methods: {
            selectCategoryItem: function (componentIndex, itemId) {
                this.createComponents[componentIndex] = itemId;

                if (this.newCategoryItems.length >= componentIndex + 1) {
                    this.newCategoryItems[componentIndex] = itemId;
                } else {
                    this.newCategoryItems.push(itemId);
                }

                this.generateCategoryString();
            },
            generateCategoryString: function () {
                var string = '';
                this.categoryString = this.newCategoryItems.join(',');
            },
            addCreateComponent: function () {
                this.createComponents.push('');
            },
            removeCreateComponent: function (componentIndex) {
                this.createComponents.splice(componentIndex, 1);

                if (this.createComponents.length === 0) {
                    this.createComponents.push('');
                }
            }
        }
    }
</script>

<style scoped>

</style>
