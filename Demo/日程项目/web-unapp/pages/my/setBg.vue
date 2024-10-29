<template>
	<view class="set_bg">
		<view class="box">
			<view class="list">
				<view class="row" v-for="(k,i) of mp_url" :key="i" @click="handleClick(i)">
					<text>{{k.name}}</text>
					<image src="/static/img/tickp.png" mode="widthFix" v-if="mp_index==i"></image>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		mapState,
		mapGetters
	} from "vuex"
	export default {
		data() {
			return {

			}
		},
		computed: {
         ...mapState(["mp_url","mp_index"])
		},
		onLoad(options) {

		},
		methods: {
            handleClick(i){
				if(this.mp_index!=i){
					this.$store.commit("updateAudioUrl",i)
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.set_bg {
		.box {
			width: 690rpx;
			border-radius: 20rpx;
			margin-bottom: 20rpx;
			background-color: #FFFFFF;
			margin: 20rpx auto;
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