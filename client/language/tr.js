import panel from "~/language/panel/tr";

export default  {
    header_title: "Dünya çapında 13.000'den fazla destinasyonda <br>  taksi ve transferi bulun",
    panel: {...panel},

    menu: {
        business: "İş için",
        corporate: "Kurumsal müşterilere",
        transport: "Nakliye şirketi",
        hotel: "Otellere",
        about: "Hakkımızda",
        delivery: "Teslim",
        contacts: "İletişim",
        login: "Giriş",
        cabinet: "Kabine",
        company: "Şirket",
        information: "Bilgi",
        terms: "Kullanım Koşulları",
        privacy: "Gizlilik Politikası",
        all_right: "Tüm hakları saklıdır",

    },
    from: "Nereden",
    to: "Nereye",
    datapicker_from: "Şehir, havaalanı veya otogar",
    datapicker_to: "Şehir, havaalanı veya otogar",
    return_back: "İleri geri",
    search: "Ara",
    search_transfer: "Transferler {from} - {to}",
    not_found_transfers: 'Maalesef bu yönde hiçbir araç bulunamadı',
    404: 'Sayfa bulunamadı',
    home_page: 'Ana sayfaya dön',


    home: {
        comfort: "Konfor",
        comfort_info: "Sadece yeni araçlar ve profesyonel sürücüler",
        easy_search: "Kolay arama",
        easy_search_info: "Seyahat sürenizi ve maliyetinizi hemen hesaplayın",
        support: "7/24 Destek",
        support_info: "Operatörlerimiz günde 24 saat iletişim halinde",
        fix_price: "Sabit fiyat",
        fix_price_info: "Nihai maliyeti hemen görüyorsunuz",
    },
    corp: {
        title: 'Seyahat firmaları için ortaklık programı',
        subtitle: 'Konuklarınız için önceden rehberli turlar rezervasyonu yapın. Konfor basit ve güvenlidir.',
    },
    transport: {
        title: 'Etkili iş kararları',
        subtitle: 'Kurumsal müşteriler için konsolide yönetim. Yüksek kaliteli hizmetler. İşin düzene sokulması.',
    },

    how_work: {
        title: "Nasıl çalışır?",
        route: "You choose the route",
        price: "Tüm transfer seçeneklerini ve sabit fiyatı gösteriyoruz",
        option: "En iyi seçeneği seçiyorsunuz",
        meet: "Şoförümüz sizi bir isim levhasıyla karşılıyor",
        final: "Sizi nihai varış noktanıza rahatça ulaştıracağız"
    },

    about: {
        title: ' Hakkımızda Lux Elit Travel',

        content: 'Lux Elit Travel is a simple and easy way to take care of your trip in advance. Travel in comfort, and our team will take care of the details.\n' +
            '\n' +
            'Our service is safe and reliable transfers at the best prices. Due to the developed partner network, Lux Elit Travel annually provides more than 30,000 transfers worldwide.\n' +
            '\n' +
            'The main idea of our service is to make your trip safe and comfortable, the booking process - quick and easy, and travel time-optimal.\n' +
            '\n' +
            'Trust our service and get an impressive trip experience!'
    },

    contacts: {
        title: 'İletişim',
        subtitle: 'İletişim ve adres',
        information: 'Bilgi',
        company_name: 'Şirket Ismi',
        address: 'Adres',
        city: 'Şehir',
        country: 'Ülke',
        email: 'E-posta',
    },
    checkout: {
        progressBar: {
            step_1: 'Transfer rezervasyonu',
            step_2: 'Ödeme',
            step_3: 'Onaylama',
        },
        paymentMethods: {
            title: 'Ödeme yöntemleri',
            card: {
                name: {
                    label: 'İsim',
                    placeholder: 'İsim soyisim'
                },
                card: {
                    label: 'Kart Numarası',
                    placeholder: 'Kart Numarası'
                },
                expiration: {
                    label: 'Son kullanma tarihi'
                },
                security: {
                    label: 'Güvenlik Kodu',
                    placeholder: 'Kod'
                }
            },
            by_card: 'Web sitesinde kartla',
            by_cash: 'Sürücüye nakit'
        },

        passengers: {
            title: 'Yolcular',
            number: 'Yolcu sayısını belirtin',
            sub_number: 'Çocuklar ve bebekler dahil',
            child_seat: 'Çocuk koltukları ekle',
            sub_child_seat: 'Çocuklar sizinle birlikte seyahat ediyorsa bu gereklidir.',
        },
        passengers_details: {
            title: 'Yolcu detayları',
            name_surname: 'Adı ve soyadı *',
            email: 'E-posta *',
            sub_email: 'Bu e-posta adresine bir rezervasyon onayı, kupon ve hatırlatıcı göndereceğiz.',
            phone: 'Ülke kodu ile telefon numarası *',
            sub_phone: 'Sizinle acil iletişim için ona ihtiyacımız var. Transfer günü müsait olmalıdır.',
            privacy: 'İletişim bilgilerinizi girerek Gizlilik Politikamızı kabul etmiş olursunuz.',
        },
        errors: {
            address: 'Adresi veya otel adını girin.',
            flight_number: 'Uçuş numarasını girin',
            name: 'Adınızı ve soyadınızı girin.',
            email: 'E-posta adresinizi girin.',
            phone: 'Telefon numarasını girin.',
        },
        transfer_details: {
            title: 'Transfer detayları',
            change: 'Değiştir',
            travel_time: 'Seyahat süresi: ~ 1 h',
            address: 'Kalkış: otel adı veya adresi *',
            flight_number: 'Uçuş Numarası *',
            return_trip: 'Dönüş yolculuğu',

            passengers: {
                title: 'Yolcular',
                number: 'Yolcu sayısını belirtin',
                sub_number: 'Çocuklar ve bebekler dahil',
                child_seat: 'Çocuk koltukları ekle',
                sub_child_seat: 'Çocuklar sizinle birlikte seyahat ediyorsa bu gereklidir.',
            },
            passengers_details: {
                title: 'Yolcu detayları',
                name_surname: 'Adı ve soyadı *',
                email: 'E-posta *',
                sub_email: 'Bu e-posta adresine bir rezervasyon onayı, kupon ve hatırlatıcı göndereceğiz.',
                phone: 'Ülke kodu ile telefon numarası *',
                sub_phone: 'Sizinle acil iletişim için ona ihtiyacımız var. Transfer günü müsait olmalıdır.',
                privacy: 'İletişim bilgilerinizi girerek Gizlilik Politikamızı kabul etmiş olursunuz.',
            },
            errors: {
                address: 'Adresi veya otel adını girin.',
                flight_number: 'Uçuş numarasını girin',
                name: 'Adınızı ve soyadınızı girin.',
                email: 'E-posta adresinizi girin.',
                phone: 'Telefon numarasını girin.',
            },

        }

    },
    success: {
        title: 'Rezervasyon Başarıyla Tamamlandı',
        name_surname: 'Ad Soyad:',
        address: 'Adres:',
        flight_number: 'Uçuş Numarası:',
        phone: 'Telefon:',
        passengers: 'Yolcu sayısı:',
        email: 'E-posta:',
        child_seat: 'Çocuk Koltuğu:',
        payment_method: 'Ödeme Yöntemi:',
        cash: 'Nakit',
        card: 'Web sitesinde kartla',
        return_trip: 'Dönüş yolculuğu:',
        price: 'Fiyat:',
        return_trip_date: 'Dönüş tarihi:',
        return_trip_time: 'Dönüş yolculuğu süresi:',
        return_trip_address: 'Dönüş yolculuğu adresi:',
        return_trip_price: 'Dönüş yolculuğu fiyatı:',
        total: 'Toplam:',
        print: 'Yazdır',
        yes: 'Evet',
        no: 'Hayır',
    }

}

