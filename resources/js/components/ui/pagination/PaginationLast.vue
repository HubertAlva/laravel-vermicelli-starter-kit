<script lang="ts" setup>
import type { PaginationLastProps } from 'reka-ui';
import { PaginationLast, useForwardProps } from 'reka-ui';
import type { HTMLAttributes } from 'vue';
import type { ButtonVariants } from '@/components/ui/button';
import { buttonVariants } from '@/components/ui/button';
import { reactiveOmit } from '@vueuse/core';
import { ChevronsRightIcon } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<PaginationLastProps & {
  size?: ButtonVariants["size"]
  class?: HTMLAttributes["class"]
}>(), {
  size: "default",
})

const delegatedProps = reactiveOmit(props, "class", "size")
const forwarded = useForwardProps(delegatedProps)
</script>

<template>
  <PaginationLast
    :class="cn(buttonVariants({ variant: 'ghost', size }), 'gap-1 px-2.5 sm:pr-2.5', props.class)"
    data-slot="pagination-last"
    v-bind="forwarded"
  >
    <slot>
      <ChevronsRightIcon />
    </slot>
  </PaginationLast>
</template>
