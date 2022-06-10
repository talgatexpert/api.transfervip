<template>
	<div class="c-message" :class="h_type">
		<div class="c-message-container">
			<div class="c-message-title">
				{{ status }}
			</div>
			<div class="c-message-text">
				{{ message }}
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "message",
	props: {
		status: '',
		message: '',
		type: ''
	},

	watch: {

	},
	data() {
		return {
			h_type: ['success', 'show'],
			close: 'c-message-close'
		}
	},
	mounted() {
		if (this.type)
			this.h_type = [JSON.parse(JSON.stringify(this.type)), 'show'];

		let time = setTimeout(e => {
			this.$data.h_type.push(this.close)
			this.h_type = this.h_type.filter(e => e !== 'show')
		}, 3000)
		// clearInterval(time)
		time =	setTimeout(() => {
			this.h_type = this.h_type.filter(e => e !== 'c-message-close');
		}, 5000)

	}
}
</script>

<style lang="scss">
.c-message {
	top: 0;
	position: fixed;
	z-index: 9999;
	right: 0;
	color: #fff;
	min-width: 250px;
	animation-name: show;
	animation-duration: 2s;
	display: none;
	max-width: 300px;

	&.show{
		display: block;
	}
	&-container {
		position: relative;
		padding: 5px 20px;

	}



	.c-message-container:before {
		display: block;
		content: "";
		height: 100%;
		width: 10px;
		top: 0;

		position: absolute;
		left: 0;
	}


	&.success {
		background: #73c473;

		.c-message-container:before {
			background: #2e9c44;
		}

	}


	&.error {
		background: #e85757;

		.c-message-container:before {
			background: #a73636;
		}

	}
	&.c-message-close{
		display: block;
		animation-name: hide;
		animation-delay: 3000ms;
	}


	.c-message-title {
		font-weight: bold;
		color: #fff;

	}

}

@keyframes show {
	from {
		display: block;
		right: -100%;
	}
	to {
		right: 0;
	}
}

@keyframes hide {
	from {
		right: 0;
	}
	to {
		right: -100%;
		display: none;
	}
}
</style>