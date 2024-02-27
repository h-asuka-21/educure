<template>
    <v-card class="test_card fill-height" outlined>
        <v-list-item three-line>
            <v-list-item-content>
                <div>{{ $selects.test_type[test.type].text }}</div>
                <v-list-item-title class="headline mb-1">{{ test.name }}</v-list-item-title>
                <v-list-item-subtitle>{{ getTestDescription(test) }}</v-list-item-subtitle>
                <v-list-item-subtitle>{{test.test_time}}分</v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar
                tile
                size="100"
            >
                <v-icon
                    color="teal"
                    x-large
                >
                    {{getTypeIcon(test.type)}}
                </v-icon>
            </v-list-item-avatar>
        </v-list-item>
        <v-card-actions>
            <v-btn text>受験する</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: "ListCard",
    props: {
        test: {
            type: Object,
            required: true
        }
    },
    methods: {
        getTestDescription(test) {
            if (test.curriculum_name) {
                return test.curriculum_name;
            }
            if (test.course_name) {
                return test.course_name;
            }
            return '';
        },
        getTypeIcon(type){
            switch (type){
                case 0:
                    return 'mdi-shoe-print';
                case 1:
                    return 'mdi-laptop';
                case 2:
                    return 'mdi-chart-line';
            }
        }
    }
}
</script>

<style scoped lang="scss">
.test_card {
    max-height: 400px;
}
</style>
