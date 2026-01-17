<script setup lang="ts">
import {
  Avatar,
  AvatarFallback,
  AvatarImage,
} from '@/components/ui/avatar'
import { Row } from '@tanstack/vue-table';
import { Payment } from './types';

interface UserAvatar {
	row: Row<Payment>
}

let props = defineProps<UserAvatar>()

// let avatar: string = props.row.getValue('avatar') ?? '/default/avatar.webp'
let avatar: string = props.row.getValue('avatar')
let name: string = props.row.getValue('name') ?? 'A'

console.log(props.row);
</script>

<template>
<div class="flex items-center gap-2">
	<Avatar class="border bg-muted p-1 h-12 w-12">
		<AvatarImage :src="avatar" :title="avatar" class="rounded-full" />
		<AvatarFallback>{{ name.charAt(0).toUpperCase() }}</AvatarFallback>
	</Avatar>

	<!-- <div class="*:data-[slot=avatar]:ring-background flex -space-x-2 *:data-[slot=avatar]:ring-2 *:data-[slot=avatar]:grayscale">
		<Avatar>
			<AvatarImage :src="avatar" :title="avatar" />
			<AvatarFallback>{{ name.charAt(0).toUpperCase() }}</AvatarFallback>
		</Avatar>
	</div> -->
</div>
</template>
