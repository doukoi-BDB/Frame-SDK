<template>
	<view class="my_page">
		<image class="adu" :src="`/static/${audio_state?'open':'closed'}.png`" mode="widthFix" @click="handleClickAdu"></image>
		<view class="box info_box" @click="toLogin">
			<view class="avt">
				<image :src="userInfo.img?userInfo.img:'/static/def.png'" mode="widthFix"></image>
			</view>
			<view class="name" @click="handleUpdate">{{userInfo.user_name?userInfo.user_name:"去登录"}}
				<!-- <view v-if="hasLogin" class="tag" :class="userInfo.volunteer==0?'disab':''"  @click="handleVolunteer">志愿者</view> -->
			</view>
			<view class="desc" v-if="hasLogin">
				简介：{{userInfo.user_content}}
			</view>
		</view>
		<view class="box list">
			<view class="row" @click="handleClickInsertText">
				<text>编辑记事本</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="handleList">
				<text>我的记事本</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="handleSetBg">
				<text>背景音乐设置</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<!-- <view class="row" @click="handleList(2)">
				<text>救助任务</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="handleList(3)">
				<text>领养列表</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view> -->
			<view class="row" @click="noStart">
				<text>帮助与反馈</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="noStart">
				<text>平台规则</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="noStart">
				<text>任务协议</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
			<view class="row" @click="noStart">
				<text>公众号</text>
				<image src="/static/img/right.png" mode="widthFix"></image>
			</view>
		</view>
		<view class="btn" style="height: 80rpx;line-height: 80rpx;border-radius: 40rpx;" v-if="hasLogin" @click="handleLoginOut">退出登录</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapGetters
	} from 'vuex'
	export default {
		data() {
			return {
				signlist: [
					"我不怕受伤，只怕努力最后没被谁记得",
					"繁华落尽谁许我一世安好",
					"幸福是可以共享的，孤独永远是自己的",
					"一入人事深似海，从此节操为路人",
					"人生自是有情痴，莫叫生死作相思",
					"最美的人鱼藏在深海，最深爱你的人值得等待",
					"越是夜深人静越就孤单相随",
					"像一块铁却是冰心里住着风亦冷自清",
					"能够在最美好的时光与你相遇、这就是幸福",
					"遇见你三生有幸，此生还请多多关照",
					"谨以此生陪你白头谨以此生等你回首",
					"纵马一生少不得故作深沉"
				],
				index: 0,
			}
		},
		computed: {
			...mapState(['userInfo','adminInfo',"audio_state"]),
			...mapGetters(["hasLogin"])
		},
		onLoad(options) {
			this.index = parseInt(Math.random() * 12)
		},
		methods: {
			handleSetBg(){
				uni.navigateTo({
					url:"/pages/my/setBg"
				})
			},
			handleClickInsertText(){
				uni.navigateTo({
					url:"/pages/send/insertText"
				})
			},
			handleClickAdu(){
				this.$store.commit("updateAudioState")
			},
			handleUpdate(){
				uni.navigateTo({
					url:this.hasLogin?"/pages/login/region":"/pages/login/index"
				})
			},
			handleLoginOut(){
				uni.showModal({
					title: "提示",
					content: "确认要退出登录吗？",
					cancelText: "取消",
					confirmText: "确认",
					success: ({
						confirm,
						cancel
					}) => {
						if (confirm) {
							this.$store.commit("logout")
						}
					}
				})
			},
			toLogin(){
				if(!this.hasLogin){
					uni.reLaunch({
						url:"/pages/login/index"
					})
				}
			},
			handleList() {
				uni.navigateTo({
					url: "/pages/my/list" 
				})
			},
			noStart() {
				this.$msg("正在努力研发中...")
			},
			handleVolunteer() {
				if (this.userInfo.volunteer == 0) {
					uni.showModal({
						title: "是否申请成为志愿者",
						cancelText: "否",
						confirmText: "是",
						success: (res) => {
							if (res.confirm) {
								this.volunteer();
							}
						}
					})
				}
			},
			async volunteer() {
				const res = await this.$api({
					url: "applyVolunteer",
					type: 'post'
				})
				if (res.code == 200) {
					this.$msg("申请提交成功")
				}
			},
			handleAdmin() {
				if (this.adminInfo.id) {
					uni.navigateTo({
						url: "/pages/admin/list"
					})
					return;
				}
				uni.navigateTo({
					url: "/pages/admin/index"
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.adu{
		position: absolute;
		top: 30rpx;
		right: 30rpx;
		width: 60rpx;
		height: 60rpx;
	}
	.my_page {
		padding: 30rpx;
		padding-top: 140rpx;
        position: relative;
		.box {
			width: 690rpx;
			border-radius: 20rpx;
			margin-bottom: 20rpx;
			background-color: #FFFFFF;
		}

		.info_box {
			position: relative;
			padding-top: 80rpx;
			padding-bottom: 20rpx;

			.avt {
				width: 180rpx;
				height: 180rpx;
				border-radius: 50%;
				position: absolute;
				top: -90rpx;
				left: 255rpx;
				background-color: #FFFFFF;
				padding: 20rpx;

				&>image {
					width: 140rpx;
					height: 140rpx;
					border-radius: 50%;
				}
			}

			.name {
				text-align: center;
				font-size: 32rpx;
				line-height: 50rpx;
				margin-top: 10rpx;

				.tag {
					display: inline-block;
					height: 40rpx;
					font-size: 30rpx;
					line-height: 40rpx;
					background-color: #FFD243;
					color: #ffffff;
					padding: 0rpx 20rpx;
					border-radius: 8rpx;
					margin-left: 20rpx;
				}

				.disab {
					background-color: #e8e8e8 !important;
					color: #888888 !important;
				}
			}

			.desc {
				padding: 0rpx 20rpx;
				font-size: 26rpx;
				margin-top: 10rpx;
				color: #FFD243;
			}
		}

		.list {
			.row {
				padding: 0rpx 20rpx;
				height: 108rpx;
				display: flex;
				justify-content: space-between;
				align-items: center;
				font-size: 28rpx;
				color: #555;

				&>image {
					width: 36rpx;
					height: 36rpx;
				}

			}

			&>.row:not(:first-child) {
				border-top: 1rpx solid #f8f8f8;
			}
		}
	}
</style>