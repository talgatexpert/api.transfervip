<template>
	<v-menu class="v-language-switcher__menu" offset-y>
		<template v-slot:activator="{ on, attrs }">

			<v-chip
					v-bind="attrs"
					v-on="on"
					class="transparent ml-3 rounded-pill py-6  v-chip--clickable"
					pill

			>
				{{ language }}
			</v-chip>
		</template>

		<v-list class="v-language-switcher__list">
			<v-list-item
					v-for="(item, index) in items"
					:key="index"
					color="primary"
					class="vnb__sub-menu-options__option__link"

					:class="item.active ? 'active' : ''"
			>

				<v-list-item-title >

					<nuxt-link :to="switchLocalePath(item.code)" class="vnb__sub-menu-options__option__link__link"
					   :aria-label="item.name" >
						<span class="vnb__sub-menu-options__option__link__icon vnb__sub-menu-options__option__link__icon--left"><i
							class="mdi mdi-circle-medium"></i></span> <span class="vnb__sub-menu-options__option__link__text-wrapper"><span
							class="vnb__sub-menu-options__option__link__text-wrapper__text">{{item.name}}</span></span>
						<!----></nuxt-link>
				</v-list-item-title>
			</v-list-item>
		</v-list>
	</v-menu>
</template>

<script>
import {useLocale} from "../../hooks/locale";

export default {
	name: "languageSwitcher",
	data() {
		return {items: [],}
	},
	computed: {
		language() {
			const language = useLocale(this.$i18n)
			return language.name
		}
	},

	mounted() {
		this.items = this.$i18n.locales.map(item => {
			item['active'] = false;
			if (this.$i18n.locale === item.iso) {
				item['active'] = true
			}
			return item
		})
	}
}
</script>

<style scoped lang="scss">
.vnb__sub-menu-options__option__link {
	cursor: pointer;
	transition: 300ms;
	color: black;
}
.vnb__sub-menu-options__option__link__link{
	color: #333;



}
.vnb__sub-menu-options__option__link:hover, .vnb__sub-menu-options__option__link.active {
	color: #007aff;
	text-decoration: none;
	background: #f3f3f3;
	border-left: 2px solid #007aff;
	cursor: pointer;

	.mdi-circle-medium{
		color:  #007aff !important;
	}
}
.vnb__sub-menu-options__option__link:hover .vnb__sub-menu-options__option__link__text-wrapper__text, .vnb__sub-menu-options__option__link.active .vnb__sub-menu-options__option__link__text-wrapper__text{
	color: #007aff !important;

}

.v-menu__content {
	box-shadow: 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%);
}
</style>