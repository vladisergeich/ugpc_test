<template>
    <Card style="overflow: hidden">
        <template #title>Заказ: </template>
        <template #subtitle>АО "Ступинский химический завод" КЛ3764</template>
        <template #content>
            <p class="m-0">
                ТЭ Рото Мат.лак BIS Магия карите 900мл
            </p>
            <Stepper :value="currentStep" class="basis-[50rem]" linear >
                <StepList>
                    <Step 
                        v-for="stage in application.stages" 
                        :key="stage.sequence_number"
                        :value="stage.sequence_number.toString()"
                        :completed="stage.status == 'completed'"
                    >
                        {{ stage.stage.name }}
                    </Step>
                </StepList>
            </Stepper>
        </template>
        <template #footer>
            <Accordion>
                <AccordionPanel value="0">
                    <AccordionHeader>Подробнее</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                            qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
                        </p>
                    </AccordionContent>
                </AccordionPanel>
            </Accordion>
        </template>
    </Card>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Card, Button, Stepper,StepList,Step,Accordion,AccordionPanel,AccordionHeader,AccordionContent } from "@danaflex/ui/components";

const props = defineProps({
    application: Object,
});


const currentStep = ref(
    props.application.stages.find(stage => stage.status === 'in_progress')?.sequence_number?.toString() || '1'
);

</script>
