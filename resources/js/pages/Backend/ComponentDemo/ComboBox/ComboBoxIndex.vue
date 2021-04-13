<template>
    <div class="combo-box-page">

        <top-header :paths="['Component demo', 'Combo box']" />

        <div class="mb-3">
            Selected:
            <span class="text-info">
                {{selectedMember.name}}
            </span>
        </div>

        <div class="mb-3">
            <combo-box placeholder="My family"
                :readonly="true"
                :options="members"
                :selected-item="selectedMember"
                @change="selectedMember = $event">
                <template v-slot:item-template="slotProps">
                    <img :src="slotProps.item.avatar || '/images/no-image.svg'"
                        class="mr-2 rounded avatar" />
                    {{slotProps.item.name}}
                </template>
            </combo-box>
        </div>

        <div class="mb-3">
            Selected:
            <span class="text-info">
                {{selectedCountry.name}}
            </span>
        </div>

        <div class="mt-3">
            <combo-box placeholder="Chọn đất nước"
                :readonly="false"
                :options="countries"
                :selected-item="selectedCountry"
                :show-clear="true"
                @change="selectedCountry = $event">
            </combo-box>
        </div>
    </div>
</template>


<script>
const COUNTRIES = [
    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Anguilla', 'Antigua & Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia',
    'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia',
    'Bosnia & Herzegovina', 'Botswana', 'Brazil', 'British Virgin Islands', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon',
    'Canada', 'Cape Verde', 'Cayman Islands', 'Central Arfrican Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Congo', 'Cook Islands', 'Costa Rica',
    'Cote D Ivoire', 'Croatia', 'Cuba', 'Curacao', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador',
    'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France',
    'French Polynesia', 'French West Indies', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guam',
    'Guatemala', 'Guernsey', 'Guinea', 'Guinea Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran',
    'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kosovo', 'Kuwait',
    'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia',
    'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova',
    'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauro', 'Nepal', 'Netherlands', 'Netherlands Antilles',
    'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama',
    'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda',
    'Saint Pierre & Miquelon', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone',
    'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'St Kitts & Nevis',
    'St Lucia', 'St Vincent', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand',
    'Timor L\'Este', 'Togo', 'Tonga', 'Trinidad & Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks & Caicos', 'Tuvalu', 'Uganda', 'Ukraine',
    'United Arab Emirates', 'United Kingdom', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
    'Virgin Islands (US)', 'Yemen', 'Zambia', 'Zimbabwe'
]
    .map(s => ({
        id: s,
        name: s
    }));

const MEMBERS = [
    { id: 'nvh', name: 'Nguyễn Văn Huyên', avatar: '/images/family/nvh.jpg' },
    { id: 'nat', name: 'Nguyễn Anh Tuấn', avatar: '/images/family/nat.jpg' },
    { id: 'cttd', name: 'Cao Thị Thùy Dương', avatar: '/images/family/cttd.jpg' }
];

export default {
    data() {
        return {
            members: MEMBERS,
            countries: COUNTRIES,
            selectedMember: {},
            selectedCountry: {
                id: 'Albania',
                name: 'xxx'
            }
        };
    }
};
</script>


<style lang="scss">
.combo-box-page {
    .autocomplete__item .avatar {
        width: 16px;
        height: 16px;
        object-fit: cover;
    }
}
</style>
