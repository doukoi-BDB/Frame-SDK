<script>
	import {
		mapGetters,
		mapState
	} from 'vuex'
	export default {
		computed: {
			...mapState(["audio_state", "audio"]),
			...mapGetters(['hasLogin']),
		},
		onLaunch: function() {
			console.log('App Launch')
			this.$store.dispatch('initData');
			uni.addInterceptor('navigateTo', {
				invoke: (args) => {
					const arr = ["/pages/login/region", "/pages/login/index"]
					if (!this.hasLogin && arr.indexOf(args.url) == -1) { //未登录提示登录
						uni.showModal({
							title: "提示",
							content: "请先登录后再操作",
							cancelText: "取消",
							confirmText: "去登录",
							success: ({
								confirm,
								cancel
							}) => {
								if (confirm) {
									uni.reLaunch({
										url: "/pages/login/index"
									})
								}
							}
						})
						args.url = "/pages/home/index"
					}
				},
				success: () => {

				},
			})
		},
		onShow: function() {
			console.log('App Show')
			if (!!this.audio && this.audio_state) {
				this.$store.commit("audioPaly", true)
			}
		},
		onHide: function() {
			console.log('App Hide')
			if (!!this.audio && this.audio_state) {
				this.$store.commit("audioPaly", false)
			}
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	page {
		height: 100%;
		background-color: #F8F8F8;
		color: #333333;
		background-image: url("@/static/page_bg.jpg");
		background-size: 100% 100%;
		background-repeat: no-repeat;
	}

	page-body {
		height: 100%;
	}

	view {
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}

	.btn {
		background-color: #FFD243;
		text-align: center;
		height: 60rpx;
		line-height: 60rpx;
		font-size: 32rpx;
		color: #fff;
		border-radius: 30rpx;
	}

	.ph {
		font-size: 28rpx;
		font-weight: 400;
		color: #7F7F8E;
	}

	.noData {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		color: #a8a8a8;
		font-size: 30rpx;
	}
</style>