import { ref, watchEffect, toValue } from 'vue'
import axios from 'axios'

export default function useFetch(url) {
	const data = ref([])
	const error = ref(null)

	const fetchData = async () => {
		data.value = []
		error.value = null

		try {
			let res = await axios.get(toValue(url))
			data.value = res.data.data
		} catch (err) {
			error.value = err
		}
	}

	watchEffect(() => {
		fetchData()
	})

	return {
		data,
		error, // In component watch error
		fetchData,
	}
}

/*
// Re-fetch when ref cheanges
const url = ref('/initial-url')
const { data, error } = useFetch(url)

// Re-fetch when props.id changes
const { data, error } = useFetch(() => `/posts/${props.id}`)

watch(error, (newValue) => {
	if (newValue) {
		// UI logic in component
		showToast("An error occurred.");
	}
});
*/