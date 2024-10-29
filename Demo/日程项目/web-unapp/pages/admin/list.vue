<template>
	<view class="admin_list">
		<view style="height: 10rpx;"></view>
		<view class="tab">
			<view :class="type==1?'act':''" @click="handleClickTab(1)">用户列表</view>
			<view :class="type==2?'act':''" @click="handleClickTab(2)">志愿者申请列表</view>
		</view>
		<scroll-view scroll-y class="scroll">
			<view class="item" v-for="k of list" :key="k.id">
				<view class="info">
					<image :src="k.img" mode="aspectFit" class="avt"></image>
					<view class="name">
						{{k.name}}
						<view class="time">{{type==1?'创建时间':'申请时间'}}: {{k.createTime}}</view>
					</view>
				</view>
				<view class="btn_box" v-if="type==2">
					<view class="btn btn_1" @click="handleClickBtn(k,1)" v-if="k.status==0">同 意</view>
					<view class="btn btn_2" @click="handleClickBtn(k,2)" v-if="k.status==0">拒 绝</view>
					<view v-if="k.status!=0" :style="{color:k.status==1?'#FFD243':'#FF6A6A'}">{{k.status==1?"已通过":"已拒绝"}}</view>
				</view>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import {mapState} from "vuex"
	export default {
		data() {
			return {
				type: 1,
				list: [],
			}
		},
		computed:{
			...mapState(['adminInfo'])
		},
		onLoad(options) {
			this.loadList();
		},
		methods: {
			handleClickTab(val) {
				this.type = val;
				this.loadList()
			},
			async loadList() {
				const res = await this.$api({
					url: this.type == 1 ? "userList" : "volunteerApplyList",
				})
				if (res.code == 200) {
					this.list = res.data;
				}
			},
			handleClickBtn({userId},type){
				uni.showModal({
					title: type==1?"确认通过志愿者申请吗？":"确认拒绝志愿者申请吗？",
					cancelText: "取消",
					confirmText: "确认",
					success: (res) => {
						if (res.confirm) {
							this.handle(userId,type);
						}
					}
				})
			},
			async handle(id, action) {
				const res = await this.$api({
					url: "volunteerApplyAudit",
					data: {
						userId: id,
						action: action, //1通过 2拒绝
					},
					type:'post'
				})
				if (res.code == 200) {
					this.$msg("操作成功！");
					this.loadList()
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.admin_list {
		height: 100%;
		display: flex;
		flex-direction: column;
		background-color: #FFFFFF;

		.scroll {
			flex: 1;
			overflow: hidden;

			& .item:not(:first-child) {
				border-top: 2rpx solid #e8e8e8;
			}

			.item {
				width: 750rpx;
				padding: 10rpx 30rpx;
				background-color: #FFFFFF;

				.info {
					display: flex;
					align-items: center;

					.avt {
						width: 120rpx;
						height: 120rpx;
						border-radius: 50%;
					}

					.name {
						font-size: 28rpx;
						padding-left: 20rpx;
						.time{
							font-size: 24rpx;
							margin-top: 8rpx;
							color: #a1a1a1;
						}
					}
				}

				.btn_box {
					display: flex;
					justify-content: flex-end;
					align-items: center;

					// height: ;
					.btn_1 {
						width: 160rpx;
						margin-right: 20rpx;
					}

					.btn_2 {
						width: 160rpx;
						background-color: #FF6A6A;
					}
				}
			}


		}

		.tab {
			width: 690rpx;
			height: 80rpx;
			margin: 0 auto;
			display: flex;
			border-radius: 10rpx;
			margin-bottom: 10rpx;
			overflow: hidden;

			&>view {
				width: 50%;
				height: 80rpx;
				text-align: center;
				line-height: 80rpx;
				font-size: 32rpx;
				background-color: #F6F7FB;
				color: #a1a1a1;
			}

			.act {
				background-color: #FFD243;
				color: #FFFFFF;
			}
		}
	}
</style>