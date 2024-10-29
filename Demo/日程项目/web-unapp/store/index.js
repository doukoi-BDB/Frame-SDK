import Vue from 'vue'
import Vuex from 'vuex'
import {
	api
} from '@/common/api.js'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		token: '',
		userInfo: {},
		adminInfo: {},
		userId: "",
		audio: null,
		audio_state: true,
		mp_url: [{
				name: "luv letter",
				url: require("@/static/mp3/bg_1.mp3")
			},
			{
				name: "biosphere - let's be lazytogether",
				url: require("@/static/mp3/bg_2.mp3")
			},
			{
				name: "Chance Thrash - ChamomileTea",
				url: require("@/static/mp3/bg_3.mp3")
			},
			{
				name: "Quadro Nuevo - Per il mioamore",
				url: require("@/static/mp3/bg_4.mp3")
			}
		],
		mp_index: 0,
	},
	getters: {
		hasLogin(state) {
			return !!state.userId || !!uni.getStorageSync('userId');
		},
	},
	mutations: {
		updateAudioUrl(state, index) {
			state.mp_index = index;
			state.audio.src = state.mp_url[index].url;
			state.audio.play();
			uni.setStorageSync("mp3_index",index)
		},
		updateAudioState(state) {
			if (!state.audio_state) {
				state.audio.play()
				uni.setStorageSync("audio_staet", 1)
			} else {
				state.audio.stop()
				uni.setStorageSync("audio_staet", 2)
			}
			state.audio_state = !state.audio_state
		},
		audioPaly(state, type) {
			if (type) {
				state.audio.play()
			} else {
				state.audio.stop()
			}
		},
		//更新state数据
		setState(state, {
			key,
			val
		}) {
			state[key] = val;
		},
		//更新userId
		setUserId(state, data) {
			state.userId = data;
			uni.setStorageSync('userId', data);
			this.dispatch('getUserInfo'); //更新用户信息
		},
		//退出登录
		logout(state) {
			uni.removeStorageSync('userId');
			uni.removeStorageSync('adminInfo');
			state.userId = '';
			state.token = '';
			setTimeout(() => {
				state.userInfo = {};
				state.adminInfo = {};
				// uni.reLaunch({
				// 	url: "/pages/login/index"
				// })
			}, 500)
		},
	},
	actions: {
		initData({
			state,
			commit,
			dispatch
		}) {
			const userId = uni.getStorageSync('userId');
			const adminInfo = uni.getStorageSync("adminInfo");
			if (adminInfo && adminInfo.id) {
				state.adminInfo = adminInfo
			}
			if (userId) {
				commit("setUserId", userId)
			} else {

			}
			const audio_staet = uni.getStorageSync("audio_staet");
			const audio = uni.createInnerAudioContext();
			const mp_index = uni.getStorageSync("mp3_index");
			let url = "";
			if (mp_index) {
				url = state.mp_url[mp_index].url
				state.mp_index = mp_index;
			} else {
				url = state.mp_url[0].url;
			}
			audio.src = url;
			audio.loop = true;
			if (audio_staet == 2) { //关闭状态
				state.audio = audio;
				state.audio_state = false;
			} else {
				audio.play();
				state.audio = audio;
				state.audio_state = true;
			}
		},
		//更新用户信息
		async getUserInfo({
			state,
			commit,
			dispatch
		}) {
			const res = await api({
				url: "getUserInfo",
				data: {
					id: uni.getStorageSync('userId')
				}
			});
			if (res.code == 200) {
				const userInfo = res.data;
				commit('setState', {
					key: 'userInfo',
					val: userInfo
				})
			}
		},
	}
})


export default store