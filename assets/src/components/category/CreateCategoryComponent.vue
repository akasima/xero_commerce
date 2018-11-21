<template>
    <div style="display: flex">
        <div style="flex-grow: 10;">
            <category-select-component v-for="(categoryItems, index) in this.allCategory"
                                       v-on:selectParent="getChild"
                                       :category-items="categoryItems"
                                       :index="index"
                                       :key="index"
                                       :selected="selected">
            </category-select-component>
        </div>
        <div style="flex-grow: 1;">
            <button v-on:click="removeCreateComponent" type="button" class="xe-btn xe-btn-danger xe-btn-block"
                    >삭제 {{componentIndex}}
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CreateCategoryComponent",
        props: [
            'categoryItems', 'getChildUrl', 'componentIndex', 'selected'
        ],
        data() {
            return {
                allCategory: [],
                isVisibleAddButton: false,
                isVisibleRemoveButton: false,
                selectItemIds: [],
                availableItemId: null,
            }
        },
        mounted() {
            this.allCategory.push(this.categoryItems);
            this.setAddButtonVisible(true);
            this.setRemoveButtonVisible(false);
        },
        methods: {
            getChild: function (parentId, index) {
                var _this = this;

                _this.allCategory.splice(index + 1);
                _this.calcAvailableItemId(parentId, index);
                _this.$emit('selectCategoryItem', this.componentIndex, this.availableItemId);

                if (parentId === '') {
                    return;
                }

                $.ajax({
                    url: this.getChildUrl,
                    type: 'get',
                    dataType: 'json',
                    data: {'parentId': parentId},
                    success: function (data) {
                        var childCategories = data.categories;

                        if (childCategories.length !== 0) {
                            _this.allCategory.push(childCategories);
                        }
                    }
                });
            },
            calcAvailableItemId: function (selectItemId, selectIndex) {
                this.selectItemIds.splice(selectIndex);

                if (selectItemId === '') {
                    if (selectIndex === 0) {
                        this.availableItemId = null;
                    } else {
                        this.availableItemId = this.selectItemIds[selectIndex - 1];
                    }
                } else {
                    this.selectItemIds.push(selectItemId);
                    this.availableItemId = selectItemId;
                }
            },
            setAddButtonVisible: function (newState) {
                this.isVisibleAddButton = newState;
            },
            setRemoveButtonVisible: function (newState) {
                this.isVisibleRemoveButton = newState;
            },
            addCreateComponent: function () {
                this.$emit('addCreateComponent');
                this.setAddButtonVisible(false);
                this.setRemoveButtonVisible(true);
            },
            removeCreateComponent: function () {
                this.$emit('removeCreateComponent', this.componentIndex);
            }
        }
    }
</script>

<style scoped>

</style>
