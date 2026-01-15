import { h } from 'vue'
import type { Payment } from './types'
import type { ColumnDef } from '@tanstack/vue-table'
import { Checkbox } from '../ui/checkbox'
import { Button } from '../ui/button'
import { statuses } from './types'
import DropdownAction from '@/components/payments/DataTableDropDown.vue'
import DataTableColumnHeader from './DataTableColumnHeader.vue'
import { ArrowUpDown } from 'lucide-vue-next'

export const columns: ColumnDef<Payment>[] = [
	{
		id: 'select',
		header: ({ table }) => h(Checkbox, {
			'modelValue': table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
			'onUpdate:modelValue': value => table.toggleAllPageRowsSelected(!!value),
			'ariaLabel': 'Select all',
			'class': 'translate-y-0.5',
		}),
		cell: ({ row }) => h(Checkbox, {
			'modelValue': row.getIsSelected(),
			'onUpdate:modelValue': value => row.toggleSelected(!!value),
			'ariaLabel': 'Select row',
			'class': 'translate-y-0.5'
		}),
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'id',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'ID' }),
		cell: ({ row }) => {
			return h('div', { class: 'text-left font-normal' }, row.getValue('id'))
		},
	},
	{
		accessorKey: 'email',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Email' }),
		cell: ({ row }) => {
			return h('div', { class: 'text-left font-normal' }, row.getValue('email'))
		},
	},
	{
		accessorKey: 'amount',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Amount' }),
		cell: ({ row }) => {
			const amount = Number.parseFloat(row.getValue('amount'))
			const formatted = new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD',
			}).format(amount)

			return h('div', { class: 'text-left font-normal' }, formatted)
		},
	},
	{
		accessorKey: 'status',
		header: ({ column }) => {
			return h(Button, {
				variant: 'ghost',
				onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
			}, () => ['Status', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
		},
		cell: ({ row }) => {
			const status = statuses.find(status => status.value === row.getValue('status'))

			if (!status)
				return null

			return h('div', { class: 'flex items-center payment-status status-' + status.value }, [
				status.icon && h(status.icon, { class: 'mr-2 h-4 w-4 text-muted-foreground' }),
				h('span', status.label),
			])
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id))
		},
		enableSorting: true,
		enableHiding: false,
	},
	{
		id: 'actions',
		enableHiding: false,
		header: () => h('div', { class: 'text-right' }, 'Action'),
		cell: ({ row }) => {
			const payment = row.original

			return h('div', { class: 'text-right' }, h(DropdownAction, {
				payment,
			}))
		},
	},
]
