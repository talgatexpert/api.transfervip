import Cookies from "js-cookie";


export const state = () => ({
  cities: [],
  cityFrom: {},
  cityTo: {},
  errors: [],
});

export const mutations = {
  SET_CITIES: (state, payload) => {
    state.cities = payload;
  },
  CHANGE_DIRECTION: (state, payload) => {
    state.cityFrom = payload.cityTo;
    state.cityTo = payload.cityFrom;
  },
  SET_DIRECTION: (state, payload) => {
    state.cityFrom = payload.cityFrom;
    state.cityTo = payload.cityTo;
  },
  CLEAR_CITY_DATA(state) {
    state.cityFrom = {};
    state.cityTo = {};
  },
  SET_ERROR(state, payload) {
    state.errors = payload
  },

}

/* Encode string to slug */
function convertToSlug(str) {

  //replace all special characters | symbols with a space
  str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
    .toLowerCase();

  // trim spaces at start and end of string
  str = str.replace(/^\s+|\s+$/gm, '');

  // replace space with dash/hyphen
  str = str.replace(/\s+/g, '-');
  return str;
  //return str;
}

const list = [
  {
    "name": "adana",
    "plate": "01",
    "latitude": "37.00167",
    "longitude": "35.32889",
    "counties": [
      "seyhan",
      "yuregir",
      "cukurova",
      "ceyhan",
      "saricam",
      "kozan",
      "imamoglu",
      "karatas",
      "karaisali",
      "pozanti",
      "yumurtalik",
      "tufanbeyli",
      "feke",
      "aladag",
      "saimbeyli"
    ]
  },
  {
    "name": "adiyaman",
    "plate": "02",
    "latitude": "37.76441",
    "longitude": "38.27629",
    "counties": [
      "merkez",
      "kahta",
      "besni",
      "golbasi",
      "gerger",
      "sincik",
      "celikhan",
      "tut",
      "samsat"
    ]
  },
  {
    "name": "afyonkarahisar",
    "plate": "03",
    "latitude": "38.75688",
    "longitude": "30.53870",
    "counties": [
      "merkez",
      "sandikli",
      "dinar",
      "bolvadin",
      "sinanpasa",
      "emirdag",
      "suhut",
      "cay",
      "ihsaniye",
      "iscehisar",
      "sultandagi",
      "cobanlar",
      "dazkiri",
      "basmakci",
      "hocalar",
      "bayat",
      "evciler",
      "kiziloren"
    ]
  },
  {
    "name": "agri",
    "plate": "04",
    "latitude": "39.71944",
    "longitude": "43.05139",
    "counties": [
      "merkez",
      "patnos",
      "dogubayazit",
      "diyadin",
      "eleskirt",
      "tutak",
      "taslicay",
      "hamur"
    ]
  },
  {
    "name": "amasya",
    "plate": "05",
    "latitude": "40.65238",
    "longitude": "35.82881",
    "counties": [
      "merkez",
      "merzifon",
      "suluova",
      "tasova",
      "gumushacikoy",
      "goynucek",
      "hamamozu"
    ]
  },
  {
    "name": "ankara",
    "plate": "06",
    "latitude": "39.92553",
    "longitude": "32.86628",
    "counties": [
      "cankaya",
      "kecioren",
      "yenimahalle",
      "mamak",
      "etimesgut",
      "sincan",
      "altindag",
      "pursaklar",
      "golbasi",
      "polatli",
      "cubuk",
      "kahramankazan",
      "beypazari",
      "elmadag",
      "sereflikochisar",
      "akyurt",
      "nallihan",
      "haymana",
      "kizilcahamam",
      "bala",
      "kalecik",
      "ayas",
      "gudul",
      "camlidere",
      "evren"
    ]
  },
  {
    "name": "antalya",
    "plate": "07",
    "latitude": "36.54936",
    "longitude": "31.99699",
    "counties": [
      "kepez",
      "muratpasa",
      "alanya",
      "manavgat",
      "konyaalti",
      "serik",
      "aksu",
      "kumluca",
      "dosemealti",
      "kas",
      "korkuteli",
      "gazipasa",
      "finike",
      "kemer",
      "elmali",
      "demre",
      "akseki",
      "gundogmus",
      "ibradi"
    ]
  },
  {
    "name": "artvin",
    "plate": "08",
    "latitude": "41.18322",
    "longitude": "41.83098",
    "counties": [
      "hopa",
      "merkez",
      "borcka",
      "yusufeli",
      "arhavi",
      "savsat",
      "ardanuc",
      "murgul",
      "kemalpasa"
    ]
  },
  {
    "name": "aydin",
    "plate": "09",
    "latitude": "37.84501",
    "longitude": "27.83963",
    "counties": [
      "efeler",
      "nazilli",
      "soke",
      "kusadasi",
      "didim",
      "cine",
      "incirliova",
      "germencik",
      "bozdogan",
      "kosk",
      "kuyucak",
      "sultanhisar",
      "karacasu",
      "yenipazar",
      "buharkent",
      "karpuzlu"
    ]
  },
  {
    "name": "balikesir",
    "plate": "10",
    "latitude": "39.55409",
    "longitude": "27.73733",
    "counties": [
      "karesi",
      "altieylul",
      "bandirma",
      "edremit",
      "gonen",
      "ayvalik",
      "burhaniye",
      "bigadic",
      "susurluk",
      "dursunbey",
      "sindirgi",
      "ivrindi",
      "erdek",
      "havran",
      "kepsut",
      "manyas",
      "savastepe",
      "balya",
      "gomec",
      "marmara"
    ]
  },
  {
    "name": "bilecik",
    "plate": "11",
    "latitude": "40.14192",
    "longitude": "29.97932",
    "counties": [
      "merkez",
      "bozuyuk",
      "osmaneli",
      "sogut",
      "golpazari",
      "pazaryeri",
      "yenipazar",
      "inhisar"
    ]
  },
  {
    "name": "bingol",
    "plate": "12",
    "latitude": "38.88472",
    "longitude": "40.49389",
    "counties": [
      "merkez",
      "genc",
      "solhan",
      "karliova",
      "adakli",
      "kigi",
      "yedisu",
      "yayladere"
    ]
  },
  {
    "name": "bitlis",
    "plate": "13",
    "latitude": "38.40115",
    "longitude": "42.10784",
    "counties": [
      "tatvan",
      "merkez",
      "guroymak",
      "ahlat",
      "hizan",
      "mutki",
      "adilcevaz"
    ]
  },
  {
    "name": "bolu",
    "plate": "14",
    "latitude": "40.73164",
    "longitude": "31.58981",
    "counties": [
      "merkez",
      "gerede",
      "mudurnu",
      "goynuk",
      "mengen",
      "yenicaga",
      "dortdivan",
      "seben",
      "kibriscik"
    ]
  },
  {
    "name": "burdur",
    "plate": "15",
    "latitude": "37.72028",
    "longitude": "30.29083",
    "counties": [
      "merkez",
      "bucak",
      "golhisar",
      "yesilova",
      "cavdir",
      "tefenni",
      "aglasun",
      "karamanli",
      "altinyayla",
      "celtikci",
      "kemer"
    ]
  },
  {
    "name": "bursa",
    "plate": "16",
    "latitude": "40.19329",
    "longitude": "29.07420",
    "counties": [
      "osmangazi",
      "yildirim",
      "nilufer",
      "gemlik",
      "mustafakemalpasa",
      "mudanya",
      "gursu",
      "karacabey",
      "orhangazi",
      "kestel",
      "yenisehir",
      "iznik",
      "orhaneli",
      "keles",
      "buyukorhan",
      "harmancik"
    ]
  },
  {
    "name": "canakkale",
    "plate": "17",
    "latitude": "40.14556",
    "longitude": "26.40858",
    "counties": [
      "merkez",
      "biga",
      "can",
      "gelibolu",
      "yenice",
      "ayvacik",
      "ezine",
      "bayramic",
      "lapseki",
      "eceabat",
      "gokceada",
      "bozcaada"
    ]
  },
  {
    "name": "cankiri",
    "plate": "18",
    "latitude": "33.58838",
    "longitude": "40.53690",
    "counties": [
      "merkez",
      "cerkes",
      "ilgaz",
      "orta",
      "sabanozu",
      "kursunlu",
      "yaprakli",
      "kizilirmak",
      "atkaracalar",
      "eldivan",
      "korgun",
      "bayramoren"
    ]
  },
  {
    "name": "corum",
    "plate": "19",
    "latitude": "40.54889",
    "longitude": "34.95333",
    "counties": [
      "merkez",
      "sungurlu",
      "osmancik",
      "iskilip",
      "alaca",
      "bayat",
      "mecitozu",
      "kargi",
      "ortakoy",
      "ugurludag",
      "dodurga",
      "oguzlar",
      "lacin",
      "bogazkale"
    ]
  },
  {
    "name": "denizli",
    "plate": "20",
    "latitude": "37.78333",
    "longitude": "29.09471",
    "counties": [
      "pamukkale",
      "merkezefendi",
      "civril",
      "acipayam",
      "tavas",
      "honaz",
      "saraykoy",
      "buldan",
      "kale",
      "cal",
      "cameli",
      "serinhisar",
      "bozkurt",
      "guney",
      "cardak",
      "bekilli",
      "beyagac",
      "babadag",
      "baklan"
    ]
  },
  {
    "name": "diyarbakir",
    "plate": "21",
    "latitude": "37.91363",
    "longitude": "40.21721",
    "counties": [
      "baglar",
      "kayapinar",
      "yenisehir",
      "ergani",
      "sur",
      "bismil",
      "silvan",
      "cinar",
      "cermik",
      "dicle",
      "kulp",
      "hani",
      "lice",
      "egil",
      "hazro",
      "kocakoy",
      "cungus"
    ]
  },
  {
    "name": "edirne",
    "plate": "22",
    "latitude": "41.67496",
    "longitude": "26.58348",
    "counties": [
      "merkez",
      "kesan",
      "uzunkopru",
      "ipsala",
      "havsa",
      "meric",
      "enez",
      "suloglu",
      "lalapasa"
    ]
  },
  {
    "name": "elazig",
    "plate": "23",
    "latitude": "38.68096",
    "longitude": "39.22639",
    "counties": [
      "merkez",
      "kovancilar",
      "karakocan",
      "palu",
      "aricak",
      "baskil",
      "maden",
      "sivrice",
      "alacakaya",
      "keban",
      "agin"
    ]
  },
  {
    "name": "erzincan",
    "plate": "24",
    "latitude": "39.73919",
    "longitude": "39.49015",
    "counties": [
      "merkez",
      "tercan",
      "uzumlu",
      "cayirli",
      "ilic",
      "kemah",
      "kemaliye",
      "otlukbeli"
    ]
  },
  {
    "name": "erzurum",
    "plate": "25",
    "latitude": "39.90861",
    "longitude": "41.27694",
    "counties": [
      "yakutiye",
      "palandoken",
      "aziziye",
      "horasan",
      "oltu",
      "pasinler",
      "karayazi",
      "hinis",
      "tekman",
      "karacoban",
      "askale",
      "senkaya",
      "cat",
      "koprukoy",
      "ispir",
      "tortum",
      "narman",
      "uzundere",
      "olur",
      "pazaryolu"
    ]
  },
  {
    "name": "eskisehir",
    "plate": "26",
    "latitude": "39.76619",
    "longitude": "30.52671",
    "counties": [
      "odunpazari",
      "tepebasi",
      "sivrihisar",
      "cifteler",
      "seyitgazi",
      "alpu",
      "mihaliccik",
      "mahmudiye",
      "beylikova",
      "inonu",
      "saricakaya",
      "gunyuzu",
      "mihalgazi",
      "han"
    ]
  },
  {
    "name": "gaziantep",
    "plate": "27",
    "latitude": "37.05944",
    "longitude": "37.3825",
    "counties": [
      "sahinbey",
      "sehitkamil",
      "nizip",
      "islahiye",
      "nurdagi",
      "araban",
      "oguzeli",
      "yavuzeli",
      "karkamis"
    ]
  },
  {
    "name": "giresun",
    "plate": "28",
    "latitude": "40.91698",
    "longitude": "38.38741",
    "counties": [
      "merkez",
      "bulancak",
      "espiye",
      "gorele",
      "tirebolu",
      "dereli",
      "sebinkarahisar",
      "kesap",
      "yaglidere",
      "alucra",
      "piraziz",
      "eynesil",
      "guce",
      "canakci",
      "dogankent",
      "camoluk"
    ]
  },
  {
    "name": "gumushane",
    "plate": "29",
    "latitude": "39.31432",
    "longitude": "40.28036",
    "counties": [
      "merkez",
      "kelkit",
      "siran",
      "kurtun",
      "torul",
      "kose"
    ]
  },
  {
    "name": "hakkari",
    "plate": "30",
    "latitude": "37.57444",
    "longitude": "43.74083",
    "counties": [
      "yuksekova",
      "merkez",
      "cukurca",
      "semdinli",
      "cukurca"
    ]
  },
  {
    "name": "hatay",
    "plate": "31",
    "latitude": "36.20000",
    "longitude": "36.16666",
    "counties": [
      "antakya",
      "iskenderun",
      "defne",
      "dortyol",
      "samandag",
      "kirikhan",
      "reyhanli",
      "arsuz",
      "altinozu",
      "hassa",
      "erzin",
      "payas",
      "belen",
      "yayladagi",
      "kumlu"
    ]
  },
  {
    "name": "isparta",
    "plate": "32",
    "latitude": "37.76800",
    "longitude": "30.56190",
    "counties": [
      "merkez",
      "yalvac",
      "egirdir",
      "sarkikaraagac",
      "gelendost",
      "keciborlu",
      "senirkent",
      "sutculer",
      "gonen",
      "uluborlu",
      "atabey",
      "aksu",
      "yenisarbademli"
    ]
  },
  {
    "name": "mersin",
    "plate": "33",
    "latitude": "36.81210",
    "longitude": "34.64147",
    "counties": [
      "tarsus",
      "toroslar",
      "akdeniz",
      "yenisehir",
      "mezitli",
      "erdemli",
      "silifke",
      "anamur",
      "mut",
      "bozyazi",
      "gulnar",
      "aydincik",
      "camliyayla"
    ]
  },
  {
    "name": "istanbul",
    "plate": "34",
    "latitude": "41.0400",
    "longitude": "28.9900",
    "counties": [
      "esenyurt",
      "kucukcekmece",
      "bagcilar",
      "umraniye",
      "pendik",
      "bahcelievler",
      "uskudar",
      "sultangazi",
      "gaziosmanpasa",
      "maltepe",
      "kartal",
      "esenler",
      "kadikoy",
      "kagithane",
      "avcilar",
      "atasehir",
      "fatih",
      "eyup",
      "sancaktepe",
      "basaksehir",
      "sariyer",
      "sultanbeyli",
      "gungoren",
      "beylikduzu",
      "zeytinburnu",
      "bayrampasa",
      "sisli",
      "beykoz",
      "arnavutkoy",
      "tuzla",
      "cekmekoy",
      "beyoglu",
      "buyukcekmece",
      "bakirkoy",
      "besiktas",
      "silivri",
      "catalca",
      "sile",
      "adalar"
    ]
  },
  {
    "name": "izmir",
    "plate": "35",
    "latitude": "38.418850",
    "longitude": "27.128720",
    "counties": [
      "buca",
      "karabaglar",
      "bornova",
      "konak",
      "karsiyaka",
      "bayrakli",
      "cigli",
      "torbali",
      "menemen",
      "gaziemir",
      "odemis",
      "kemalpasa",
      "bergama",
      "aliaga",
      "menderes",
      "tire",
      "balcova",
      "narlidere",
      "urla",
      "kiraz",
      "dikili",
      "cesme",
      "bayindir",
      "seferihisar",
      "selcuk",
      "guzelbahce",
      "foca",
      "kinik",
      "beydag",
      "karaburun"
    ]
  },
  {
    "name": "kars",
    "plate": "36",
    "latitude": "40.60199",
    "longitude": "43.09495",
    "counties": [
      "merkez",
      "kagizman",
      "sarikamis",
      "selim",
      "digor",
      "arpacay",
      "akyaka",
      "susuz"
    ]
  },
  {
    "name": "kastamonu",
    "plate": "37",
    "latitude": "41.37805",
    "longitude": "33.77528",
    "counties": [
      "merkez",
      "tosya",
      "taskopru",
      "cide",
      "inebolu",
      "arac",
      "devrekani",
      "bozkurt",
      "daday",
      "azdavay",
      "catalzeytin",
      "kure",
      "doganyurt",
      "ihsangazi",
      "pinarbasi",
      "senpazar",
      "abana",
      "seydiler",
      "hanonu",
      "agli"
    ]
  },
  {
    "name": "kayseri",
    "plate": "38",
    "latitude": "38.73480",
    "longitude": "35.46798",
    "counties": [
      "melikgazi",
      "kocasinan",
      "talas",
      "develi",
      "yahyali",
      "bunyan",
      "incesu",
      "pinarbasi",
      "tomarza",
      "yesilhisar",
      "sarioglan",
      "hacilar",
      "sariz",
      "akkisla",
      "felahiye",
      "ozvatan"
    ]
  },
  {
    "name": "kirklareli",
    "plate": "39",
    "latitude": "41.73508",
    "longitude": "27.22521",
    "counties": [
      "luleburgaz",
      "merkez",
      "babaeski",
      "vize",
      "pinarhisar",
      "demirkoy",
      "pehlivankoy",
      "kofcaz"
    ]
  },
  {
    "name": "kirsehir",
    "plate": "40",
    "latitude": "39.14583",
    "longitude": "34.16389",
    "counties": [
      "merkez",
      "kaman",
      "mucur",
      "cicekdagi",
      "akpinar",
      "boztepe",
      "akcakent"
    ]
  },
  {
    "name": "kocaeli",
    "plate": "41",
    "latitude": "40.85327",
    "longitude": "29.88152",
    "counties": [
      "gebze",
      "izmit",
      "darica",
      "korfez",
      "golcuk",
      "derince",
      "cayirova",
      "kartepe",
      "basiskele",
      "karamursel",
      "kandira",
      "dilovasi"
    ]
  },
  {
    "name": "konya",
    "plate": "42",
    "latitude": "37.87464",
    "longitude": "32.49315",
    "counties": [
      "selcuklu",
      "meram",
      "karatay",
      "eregli",
      "aksehir",
      "beysehir",
      "cumra",
      "seydisehir",
      "ilgin",
      "cihanbeyli",
      "kulu",
      "karapinar",
      "kadinhani",
      "sarayonu",
      "bozkir",
      "yunak",
      "doganhisar",
      "huyuk",
      "altinekin",
      "hadim",
      "celtik",
      "emirgazi",
      "tuzlukcu",
      "derebucak",
      "akoren",
      "halkapinar",
      "yalihuyuk"
    ]
  },
  {
    "name": "kutahya",
    "plate": "43",
    "latitude": "39.41995",
    "longitude": "29.98573",
    "counties": [
      "merkez",
      "tavsanli",
      "simav",
      "gediz",
      "emet",
      "altintas",
      "domanic",
      "hisarcik",
      "aslanapa",
      "cavdarhisar",
      "saphane",
      "pazarlar",
      "dumlupinar"
    ]
  },
  {
    "name": "malatya",
    "plate": "44",
    "latitude": "38.35536",
    "longitude": "38.33352",
    "counties": [
      "battalgazi",
      "yesilyurt",
      "dogansehir",
      "akcadag",
      "darende",
      "hekimhan",
      "puturge",
      "yazihan",
      "arapgir",
      "arguvan",
      "kuluncak",
      "kale",
      "doganyol"
    ]
  },
  {
    "name": "manisa",
    "plate": "45",
    "latitude": "38.61403",
    "longitude": "27.42956",
    "counties": [
      "yunusemre",
      "sehzadeler",
      "akhisar",
      "salihli",
      "turgutlu",
      "soma",
      "alasehir",
      "saruhanli",
      "kula",
      "kirkagac",
      "demirci",
      "sarigol",
      "gordes",
      "selendi",
      "ahmetli",
      "golmarmara",
      "koprubasi"
    ]
  },
  {
    "name": "kahramanmaras",
    "plate": "46",
    "latitude": "37.57527",
    "longitude": "36.92282",
    "counties": [
      "onikisubat",
      "dulkadiroglu",
      "elbistan",
      "afsin",
      "turkoglu",
      "pazarcik",
      "goksun",
      "andirin",
      "caglayancerit",
      "nurhak",
      "ekinozu"
    ]
  },
  {
    "name": "mardin",
    "plate": "47",
    "latitude": "37.31290",
    "longitude": "40.73395",
    "counties": [
      "kiziltepe",
      "artuklu",
      "midyat",
      "nusaybin",
      "derik",
      "mazidagi",
      "dargecit",
      "savur",
      "yesilli",
      "omerli"
    ]
  },
  {
    "name": "mugla",
    "plate": "48",
    "latitude": "37.18358",
    "longitude": "28.48639",
    "counties": [
      "bodrum",
      "fethiye",
      "milas",
      "mentese",
      "marmaris",
      "seydikemer",
      "ortaca",
      "yatagan",
      "dalaman",
      "koycegiz",
      "ula",
      "datca",
      "kavaklidere"
    ]
  },
  {
    "name": "mus",
    "plate": "49",
    "latitude": "38.73456",
    "longitude": "41.49103",
    "counties": [
      "merkez",
      "bulanik",
      "malazgirt",
      "varto",
      "haskoy",
      "korkut"
    ]
  },
  {
    "name": "nevsehir",
    "plate": "50",
    "latitude": "38.62469",
    "longitude": "34.71415",
    "counties": [
      "merkez",
      "urgup",
      "avanos",
      "gulsehir",
      "derinkuyu",
      "acigol",
      "kozakli",
      "hacibektas"
    ]
  },
  {
    "name": "nigde",
    "plate": "51",
    "latitude": "37.96977",
    "longitude": "34.67660",
    "counties": [
      "merkez",
      "bor",
      "ciftlik",
      "ulukisla",
      "altunhisar",
      "camardi"
    ]
  },
  {
    "name": "ordu",
    "plate": "52",
    "latitude": "40.98616",
    "longitude": "37.87972",
    "counties": [
      "altinordu",
      "unye",
      "fatsa",
      "golkoy",
      "persembe",
      "kumru",
      "aybasti",
      "korgan",
      "akkus",
      "ulubey",
      "mesudiye",
      "ikizce",
      "gurgentepe",
      "catalpinar",
      "caybasi",
      "kabatas",
      "camas",
      "gulyali"
    ]
  },
  {
    "name": "rize",
    "plate": "53",
    "latitude": "41.02551",
    "longitude": "40.51766",
    "counties": [
      "merkez",
      "cayeli",
      "ardesen",
      "pazar",
      "findikli",
      "guneysu",
      "kalkandere",
      "iyidere",
      "derepazari",
      "camlihemsin",
      "ikizdere",
      "hemsin"
    ]
  },
  {
    "name": "sakarya",
    "plate": "54",
    "latitude": "40.77307",
    "longitude": "30.39481",
    "counties": [
      "adapazari",
      "serdivan",
      "akyazi",
      "erenler",
      "hendek",
      "karasu",
      "geyve",
      "arifiye",
      "sapanca",
      "pamukova",
      "ferizli",
      "kaynarca",
      "kocaali",
      "karapurcek",
      "tarakli"
    ]
  },
  {
    "name": "samsun",
    "plate": "55",
    "latitude": "41.27970",
    "longitude": "36.33606",
    "counties": [
      "ilkadim",
      "atakum",
      "bafra",
      "carsamba",
      "canik",
      "vezirkopru",
      "terme",
      "tekkekoy",
      "havza",
      "alacam",
      "19 mayis",
      "ayvacik",
      "kavak",
      "salipazari",
      "asarcik",
      "ladik",
      "yakakent"
    ]
  },
  {
    "name": "siirt",
    "plate": "56",
    "latitude": "37.92740",
    "longitude": "41.94197",
    "counties": [
      "merkez",
      "kurtalan",
      "pervari",
      "baykan",
      "sirvan",
      "eruh",
      "tillo"
    ]
  },
  {
    "name": "sinop",
    "plate": "57",
    "latitude": "41.55947",
    "longitude": "34.85805",
    "counties": [
      "merkez",
      "boyabat",
      "gerze",
      "ayancik",
      "duragan",
      "turkeli",
      "erfelek",
      "dikmen",
      "sarayduzu"
    ]
  },
  {
    "name": "sivas",
    "plate": "58",
    "latitude": "39.75054",
    "longitude": "37.01502",
    "counties": [
      "merkez",
      "sarkisla",
      "yildizeli",
      "susehri",
      "gemerek",
      "zara",
      "kangal",
      "gurun",
      "divrigi",
      "koyulhisar",
      "altinyayla",
      "hafik",
      "ulas",
      "imranli",
      "akincilar",
      "gulova",
      "dogansar"
    ]
  },
  {
    "name": "tekirdag",
    "plate": "59",
    "latitude": "40.97809",
    "longitude": "27.51167",
    "counties": [
      "corlu",
      "suleymanpasa",
      "cerkezkoy",
      "kapakli",
      "ergene",
      "malkara",
      "saray",
      "hayrabolu",
      "sarkoy",
      "muratli",
      "marmaraereglisi"
    ]
  },
  {
    "name": "tokat",
    "plate": "60",
    "latitude": "40.32346",
    "longitude": "36.55219",
    "counties": [
      "merkez",
      "erbaa",
      "turhal",
      "niksar",
      "zile",
      "resadiye",
      "almus",
      "pazar",
      "basciftlik",
      "yesilyurt",
      "artova",
      "sulusaray"
    ]
  },
  {
    "name": "trabzon",
    "plate": "61",
    "latitude": "41.00269",
    "longitude": "39.71676",
    "counties": [
      "ortahisar",
      "akcaabat",
      "arakli",
      "of",
      "yomra",
      "arsin",
      "vakfikebir",
      "surmene",
      "macka",
      "besikduzu",
      "carsibasi",
      "tonya",
      "duzkoy",
      "caykara",
      "salpazari",
      "hayrat",
      "koprubasi",
      "dernekpazari"
    ]
  },
  {
    "name": "tunceli",
    "plate": "62",
    "latitude": "39.10617",
    "longitude": "39.54825",
    "counties": [
      "merkez",
      "pertek",
      "mazgirt",
      "cemisgezek",
      "hozat",
      "ovacik",
      "pulumur",
      "nazimiye"
    ]
  },
  {
    "name": "sanliurfa",
    "plate": "63",
    "latitude": "37.16740",
    "longitude": "38.79551",
    "counties": [
      "eyyubiye",
      "haliliye",
      "siverek",
      "viransehir",
      "karakopru",
      "akcakale",
      "suruc",
      "birecik",
      "ceylanpinar",
      "harran",
      "bozova",
      "hilvan",
      "halfeti"
    ]
  },
  {
    "name": "usak",
    "plate": "64",
    "latitude": "38.67422",
    "longitude": "29.40588",
    "counties": [
      "merkez",
      "banaz",
      "esme",
      "sivasli",
      "ulubey",
      "karahalli"
    ]
  },
  {
    "name": "van",
    "plate": "65",
    "latitude": "38.50120",
    "longitude": "43.37297",
    "counties": [
      "ipekyolu",
      "ercis",
      "tusba",
      "edremit",
      "ozalp",
      "caldiran",
      "baskale",
      "muradiye",
      "gurpinar",
      "gevas",
      "saray",
      "catak",
      "bahcesaray"
    ]
  },
  {
    "name": "yozgat",
    "plate": "66",
    "latitude": "39.82104",
    "longitude": "34.80857",
    "counties": [
      "merkez",
      "sorgun",
      "akdagmadeni",
      "yerkoy",
      "sarikaya",
      "bogazliyan",
      "cekerek",
      "sefaatli",
      "saraykent",
      "cayiralan",
      "kadisehri",
      "aydincik",
      "yenifakilli",
      "candir"
    ]
  },
  {
    "name": "zonguldak",
    "plate": "67",
    "latitude": "41.45352",
    "longitude": "31.78937",
    "counties": [
      "eregli",
      "merkez",
      "caycuma",
      "devrek",
      "kozlu",
      "alapli",
      "kilimli",
      "gokcebey"
    ]
  },
  {
    "name": "aksaray",
    "plate": "68",
    "latitude": "38.36862",
    "longitude": "34.02970",
    "counties": [
      "merkez",
      "ortakoy",
      "eskil",
      "gulagac",
      "guzelyurt",
      "agacoren",
      "sariyahsi",
      "sultanhani"
    ]
  },
  {
    "name": "bayburt",
    "plate": "69",
    "latitude": "40.26032",
    "longitude": "40.22804",
    "counties": [
      "merkez",
      "demirozu",
      "aydintepe"
    ]
  },
  {
    "name": "karaman",
    "plate": "70",
    "latitude": "37.18100",
    "longitude": "33.22224",
    "counties": [
      "merkez",
      "ermenek",
      "sarievliler",
      "ayranci",
      "kazimkarabekir",
      "basyayla"
    ]
  },
  {
    "name": "kirikkale",
    "plate": "71",
    "latitude": "39.83978",
    "longitude": "33.50887",
    "counties": [
      "merkez",
      "yahsihan",
      "keskin",
      "delice",
      "bahsili",
      "sulakyurt",
      "baliseyh",
      "karakecili",
      "celebi"
    ]
  },
  {
    "name": "batman",
    "plate": "72",
    "latitude": "37.88951",
    "longitude": "41.12928",
    "counties": [
      "merkez",
      "kozluk",
      "besiri",
      "sason",
      "gercus",
      "hasankeyf"
    ]
  },
  {
    "name": "sirnak",
    "plate": "73",
    "latitude": "37.51897",
    "longitude": "42.45371",
    "counties": [
      "cizre",
      "silopi",
      "merkez",
      "idil",
      "uludere",
      "beytussebap",
      "guclukonak"
    ]
  },
  {
    "name": "bartin",
    "plate": "74",
    "latitude": "41.63760",
    "longitude": "32.33381",
    "counties": [
      "merkez",
      "ulus",
      "amasra",
      "kurucasile"
    ]
  },
  {
    "name": "ardahan",
    "plate": "75",
    "latitude": "41.11295",
    "longitude": "42.70228",
    "counties": [
      "merkez",
      "gole",
      "cildir",
      "hanak",
      "posof",
      "damal"
    ]
  },
  {
    "name": "igdir",
    "plate": "76",
    "latitude": "39.92005",
    "longitude": "44.04361",
    "counties": [
      "merkez",
      "tuzluca",
      "aralik",
      "karakoyunlu"
    ]
  },
  {
    "name": "yalova",
    "plate": "77",
    "latitude": "40.65489",
    "longitude": "29.28418",
    "counties": [
      "merkez",
      "ciftlikkoy",
      "cinarcik",
      "altinova",
      "armutlu",
      "termal"
    ]
  },
  {
    "name": "karabuk",
    "plate": "78",
    "latitude": "41.19562",
    "longitude": "32.62265",
    "counties": [
      "merkez",
      "safranbolu",
      "yenice",
      "eskipazar",
      "eflani",
      "ovacik"
    ]
  },
  {
    "name": "kilis",
    "plate": "79",
    "latitude": "36.71647",
    "longitude": "37.11466",
    "counties": [
      "merkez",
      "musabeyli",
      "elbeyli",
      "polateli"
    ]
  },
  {
    "name": "osmaniye",
    "plate": "80",
    "latitude": "37.07462",
    "longitude": "36.2464",
    "counties": [
      "merkez",
      "kadirli",
      "duzici",
      "bahce",
      "toprakkale",
      "sumbas",
      "hasanbeyli"
    ]
  },
  {
    "name": "duzce",
    "plate": "81",
    "latitude": "40.87705",
    "longitude": "31.31927",
    "counties": [
      "merkez",
      "akcakoca",
      "kaynasli",
      "golyaka",
      "cilimli",
      "yigilca",
      "gumusova",
      "cumayeri"
    ]
  }
];

export const actions = {
  async LOAD_CITY({commit}, payload) {
    switch (this.$i18n.locale) {
      case "tr":
        await this.$axios.get('transfer/cities?search=' + payload.search + '&orderby=turkish&limit=5&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
      case "ru":
        await this.$axios.get('transfer/cities?search=' + payload.search  + '&orderby=russian&limit=10&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
      case "en":
        await this.$axios.get('transfer/cities?search=' + payload.search  + '&orderby=english&limit=10&city=' + payload.city).then(result => {
          commit('SET_CITIES', result.data.data.cities)
        }).catch(error => {
          commit('SET_ERROR', error)
        });
        break
    }
  },
  SET_DIRECTION({commit}, payload) {
    commit('SET_DIRECTION', payload);

  },
  CHANGE_DIRECTION({commit}, payload) {
    commit('CHANGE_DIRECTION', payload);
  },
  GET_CITY_BY_SLUG({commit}, payload) {
    let result = {}
    if (payload.from) {
      result['cityFrom'] = {
        name: list.filter(item => item.name === payload.from)[0].name,
        slug: list.filter(item => item.name === payload.from)[0].name,
      };
    }
    if (payload.to) {
      result['cityTo'] = {
        name: list.filter(item => item.name === payload.to)[0].name,
        slug: list.filter(item => item.name === payload.to)[0].name,
      };
    }
    commit('SET_DIRECTION', result);
  },
  CLEAR_CITY_DATA({commit}, payload) {
    commit('CLEAR_CITY_DATA');
  },
}
export const getters = {
  getCities: state => state.cities,
  getCityFrom: state => state.cityFrom,
  getCityTo: state => state.cityTo,
  GET_ERRORS: state => state.errors,
}
