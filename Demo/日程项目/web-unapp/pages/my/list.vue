<template>
	<view class="my_list">
		<notItem v-for="k of list" :item="k" :key="k.id" @del="handleDel"></notItem>
		<view class="noData" v-if="!list.length">暂无数据</view>
	</view>
</template>

<script>
	import notItem from "@/components/not-item/index.vue"
	export default {
		components: {
			notItem
		},
		data() {
			return {
				type: '',
				list: [],
			}
		},
		onLoad(options) {
			this.loadList();
		},
		methods: {
			async loadList(rest) {
				if (rest) {
					this.list = []
				}
				const res = await this.$api({
					url: "getTextList",
				})
				if (res.code == 200) {
					this.list = res.data
				}
			},
			handleDel(item) {
				uni.showModal({
					title: "提示",
					content: "确认是否删除？",
					cancelText: "否",
					confirmText: "是",
					success: async (r) => {
						const {
							confirm,
							cancel
						} = r;
						if (confirm) { //确认删除
							const res = await this.$api({
								url: "delText",
								data: {
									id: item.id,
									uid: uni.getStorageSync('userId')
								}
							})
							if (res.code == 200) {
								this.$msg("删除成功！")
								this.loadList(true);
							}
						}
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
</style>