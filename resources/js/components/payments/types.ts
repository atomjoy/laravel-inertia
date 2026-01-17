import { h } from 'vue'
import {
	CheckCircle,
	CircleOff,
	HelpCircle,
	Timer,
} from 'lucide-vue-next'

export interface Payment {
	id: string
	status: 'pending' | 'success' | 'failed' | 'canceled' | 'processing'
	amount: number
	email: string
	name?: string | null
	avatar?: string | null,
}

// Statuses
export const statuses = [
	{
		value: 'failed',
		label: 'Failed',
		icon: h(CircleOff),
	},
	{
		value: 'pending',
		label: 'Pending',
		icon: h(HelpCircle),
	},
	{
		value: 'canceled',
		label: 'Canceled',
		icon: h(CircleOff),
	},
	{
		value: 'success',
		label: 'Success',
		icon: h(CheckCircle),
	},
	{
		value: 'processing',
		label: 'Processing',
		icon: h(Timer),
	},
]

export const filter_status = {
	title: 'Filter status',
	column: 'status',
	data: statuses
}

// Table data
export const payments: Payment[] = [
	{
		id: '1',
		amount: 10,
		status: 'success',
		email: 'm@example.com',
		avatar: '/default/avatar.webp',
		name: 'Damian Bigos',
	},
	{
		id: '2',
		amount: 20,
		status: 'failed',
		email: 'example@gmail.com',
		avatar: '/default/man.webp',
		name: 'Mark Drumb',
	},
	{
		id: '3',
		amount: 30,
		status: 'pending',
		email: 'm@example.com',
		avatar: '/default/woman.webp',
		name: 'Max Gold',
	},
	{
		id: '4',
		amount: 45,
		status: 'processing',
		email: 'example@gmail.com',
		avatar: '/default/zebra.webp',
		name: 'Stanley Brave',
	},
	{
		id: '5',
		amount: 100,
		status: 'success',
		email: 'm@example.com',
		avatar: '/default/donkey.webp',
		name: 'Mary Bigel',
	},
	{
		id: '6',
		amount: 125,
		status: 'processing',
		email: 'example@gmail.com',
		avatar: '/default/avatar.webp',
		name: 'John Doe',
	},
	{
		id: '7',
		amount: 100,
		status: 'pending',
		email: 'm@example.com',
		avatar: '/default/man.webp',
		name: 'Ben Worm',
	},
	{
		id: '8',
		amount: 125,
		status: 'success',
		email: 'example@gmail.com',
		avatar: '/default/woman.webp',
		name: 'Alice Worm',
	},
	{
		id: '9',
		amount: 100,
		status: 'failed',
		email: 'm@example.com',
		avatar: '/default/zebra.webp',
		name: 'Kate Broke',
	},
	{
		id: '10',
		amount: 125,
		status: 'processing',
		email: 'example@gmail.com',
		avatar: '/default/avatar.webp',
		name: 'Kate Moore',
	},
	{
		id: '11',
		amount: 10,
		status: 'pending',
		email: 'm@example.com',
		avatar: '/default/donkey.webp',
		name: 'Alex Moore',
	},
	{
		id: '12',
		amount: 15,
		status: 'canceled',
		email: 'ele@gmail.com',
		avatar: '/default/avatar.webp',
		name: 'Alex',
	},
]
