// Location data
const vietnamLocations = {
    districts: {
        'TP. Hồ Chí Minh': ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7',
            'Quận 8', 'Quận 9', 'Quận 10', 'Quận 11', 'Quận 12', 'Thủ Đức', 'Bình Thạnh', 'Gò Vấp',
            'Phú Nhuận', 'Tân Bình', 'Tân Phú', 'Bình Tân', 'Bình Chánh', 'Củ Chi', 'Hóc Môn',
            'Nhà Bè', 'Cần Giờ'
        ]  
    },
    wards: {
        'Quận 1': ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Kho', 'Phường Cầu Ông Lãnh',
            'Phường Cô Giang', 'Phường Đa Kao', 'Phường Nguyễn Cư Trinh', 'Phường Nguyễn Thái Bình',
            'Phường Phạm Ngũ Lão', 'Phường Tân Định'
        ],
        'Quận 3': ['Phường 01', 'Phường 02', 'Phường 03', 'Phường 04', 'Phường 05', 'Phường 09',
            'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14'
        ],
        'Quận 10': ['Phường 01', 'Phường 02', 'Phường 04', 'Phường 05', 'Phường 06', 'Phường 07',
            'Phường 08', 'Phường 09', 'Phường 10', 'Phường 11', 'Phường 12', 'Phường 13',
            'Phường 14', 'Phường 15'
        ],
        'Bình Thạnh': ['Phường 01', 'Phường 02', 'Phường 03', 'Phường 05', 'Phường 06', 'Phường 07',
            'Phường 11', 'Phường 12', 'Phường 13', 'Phường 14', 'Phường 15', 'Phường 17',
            'Phường 19', 'Phường 21', 'Phường 22', 'Phường 24', 'Phường 25', 'Phường 26',
            'Phường 27', 'Phường 28'
        ],
    }
};

// Initialize location cascading
function initLocationCascade() {
    // Handle all province selects
    $(document).on('change', '.location-province', function () {
        const province = $(this).val();
        const container = $(this).closest('.mb-3').parent();
        const districtSelect = container.find('.location-district');
        const wardSelect = container.find('.location-ward');

        districtSelect.html('<option value="">Chọn...</option>');
        wardSelect.html('<option value="">Chọn...</option>');

        if (province && vietnamLocations.districts[province]) {
            vietnamLocations.districts[province].forEach(function (district) {
                districtSelect.append('<option value="' + district + '">' + district + '</option>');
            });
        }
    });

    // Handle all district selects
    $(document).on('change', '.location-district', function () {
        const district = $(this).val();
        const container = $(this).closest('.mb-3').parent();
        const wardSelect = container.find('.location-ward');

        wardSelect.html('<option value="">Chọn...</option>');

        if (district && vietnamLocations.wards[district]) {
            vietnamLocations.wards[district].forEach(function (ward) {
                wardSelect.append('<option value="' + ward + '">' + ward + '</option>');
            });
        } else if (district) {
            // Default wards if not in the specific list
            for (let i = 1; i <= 10; i++) {
                wardSelect.append('<option value="Phường ' + i + '">Phường ' + i + '</option>');
            }
        }
    });

    // Initialize existing values on page load
    $('.location-province').each(function () {
        const provinceSelect = $(this);
        const currentProvince = provinceSelect.data('current') || provinceSelect.val();
        const container = provinceSelect.closest('.mb-3').parent();
        const districtSelect = container.find('.location-district');
        const wardSelect = container.find('.location-ward');
        const currentDistrict = districtSelect.data('current');
        const currentWard = wardSelect.data('current');

        if (currentProvince && vietnamLocations.districts[currentProvince]) {
            districtSelect.html('<option value="">Chọn...</option>');
            vietnamLocations.districts[currentProvince].forEach(function (district) {
                const selected = district === currentDistrict ? 'selected' : '';
                districtSelect.append('<option value="' + district + '" ' + selected + '>' + district + '</option>');
            });

            if (currentDistrict) {
                wardSelect.html('<option value="">Chọn...</option>');
                if (vietnamLocations.wards[currentDistrict]) {
                    vietnamLocations.wards[currentDistrict].forEach(function (ward) {
                        const selected = ward === currentWard ? 'selected' : '';
                        wardSelect.append('<option value="' + ward + '" ' + selected + '>' + ward + '</option>');
                    });
                } else {
                    for (let i = 1; i <= 10; i++) {
                        const ward = 'Phường ' + i;
                        const selected = ward === currentWard ? 'selected' : '';
                        wardSelect.append('<option value="' + ward + '" ' + selected + '>' + ward + '</option>');
                    }
                }
            }
        }
    });
}

// Initialize on document ready
$(document).ready(function () {
    initLocationCascade();
});

// Re-initialize when modal is shown
$(document).on('show.bs.modal', function () {
    setTimeout(initLocationCascade, 100);
});
