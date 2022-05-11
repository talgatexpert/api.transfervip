<template>
	<div class="container login-page">
		<div class="content-info">

			<form class="form form-container">
				<b-tabs content-class="mt-3">
					<b-tab title="For Users" active>
						<div class="form-group">
							<label for="client.username">Username or email</label>
							<input type="text" v-model="email" placeholder="client@mail.com" class="form-control form-input"
							       id="client.username">
<!--							<span class="error-form" v-if="errors.email">{{ errors.email }}</span>-->
						</div>
						<div class="form-group">
							<label for="client.password">Password</label>
							<input type="password" v-model="password" placeholder="Password" class="form-control form-input"
							       id="client.password">
<!--							<span class="error-form" v-if="errors.password">{{ errors.password }}</span>-->

						</div>

						<button class="btn btn-success" @click.prevent="login">Login</button>
<!--						<span class="error-form pl-2" v-if="errors.credential">{{ errors.credential }}</span>-->

					</b-tab>
				</b-tabs>

			</form>
		</div>
	</div>
</template>

<script>
export default {
	name: "login",
	data() {
		return {
			email: "client@mail.com",
			password: "123",
			errors: []
		}
	},
	middleware: 'authenticated',


	methods: {
		async login() {
			this.errors = [];
			await this.$auth.loginWith('laravelSanctum', {
				data: {
					email: this.email,
					password: this.password,
				},
				url: '/login',
			}).then(result => {
				this.$auth.setUser(result.data.data)
				this.$auth.setUserToken(result.data.data.token, 'token')
			}).catch(error => {
				console.log(error)

				if (error.result?.data !== null) {
					this.errors = error.response?.data?.errors;
				}else{
					this.errors = []
				}
			})
		}
	}

}
</script>

