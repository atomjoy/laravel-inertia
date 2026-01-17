<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Edit, Trash, Copy, View } from 'lucide-vue-next'
import type { Payment } from './types'

const props = defineProps<{
	payment: Payment,
	// Or with callback function (update columns.ts first)
	// onEdit: Function,
}>()

const edit = (id: any) => {
	console.log("Dropdown edit", id);
    // props.onEdit(id);
}

const remove = (id: any) => {
	console.log("Dropdown remove", id);
    // props.onEdit(id);
}

function copy(id: string) {
	navigator.clipboard.writeText(id)
    console.log("Dropdown copy:", id);
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
		<Button variant="ghost" class="w-8 h-8 p-0">
			<span class="sr-only">Open menu</span>
			<MoreHorizontal class="w-4 h-4" />
		</Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
		<DropdownMenuLabel>Actions</DropdownMenuLabel>
		<DropdownMenuItem @click="edit(payment.id)">
			<Edit class="mr-2 h-4 w-4 text-muted-foreground/70" /> Edit
		</DropdownMenuItem>
		<DropdownMenuItem @click="copy(payment.id)">
			<Copy class="mr-2 h-4 w-4 text-muted-foreground/70" /> Copy ID
		</DropdownMenuItem>
		<DropdownMenuSeparator />
		<DropdownMenuItem @click="remove(payment.id)" class="text-rose-600">
			<Trash class="mr-2 h-4 w-4" /> Delete
		</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
