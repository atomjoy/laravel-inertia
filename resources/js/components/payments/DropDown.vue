<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { Row } from '@tanstack/vue-table';
import { Payment } from './types';

interface myProps {
	row: Row<Payment>,
	// Or with callback function (update columns.ts first)
	// onEdit: Function,
}

let props = defineProps<myProps>();

const edit = (id: any) => {
	// props.onEdit(id);
	console.log("Edit", id);
}

const remove = (id: any) => {
	// props.onEdit(id);
	console.log("Edit", id);
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
            <DropdownMenuItem @click="edit(row.getValue('id'))">Edit</DropdownMenuItem>
            <DropdownMenuItem @click="remove(row.getValue('id'))">Delete</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
