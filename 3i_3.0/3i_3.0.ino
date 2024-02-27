#include <Keypad.h>
#include <SPI.h>
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>
#include <Servo.h>


#define SS_PIN 53
#define RST_PIN 48
#define BUZZER_PIN 31 // Pin connected to the positive (longer) leg of the buzzer

MFRC522 mfrc522(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 20, 4);

const byte ROW_NUM    = 4; // four rows
const byte COLUMN_NUM = 4; // four columns

Servo myservo;

int servoPin= 13;
int val;


char keys[ROW_NUM][COLUMN_NUM] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};

byte pin_rows[ROW_NUM] = {9, 8, 7, 6}; // connect to the row pinouts of the keypad
byte pin_column[COLUMN_NUM] = {5, 4, 3, 2}; // connect to the column pinouts of the keypad

Keypad keypad = Keypad(makeKeymap(keys), pin_rows, pin_column, ROW_NUM, COLUMN_NUM);

void setup() {
  Serial.begin(9600);
  SPI.begin();
  mfrc522.PCD_Init();
  lcd.backlight();
  lcd.begin(20, 4);
  


  pinMode(BUZZER_PIN, OUTPUT);
  
  lcd.print("ATTENDANCE &");
  lcd.setCursor(0,1);
  lcd.print("HALLPASS");
}

void loop() {
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Card UID:");
    (mfrc522.uid.uidByte, mfrc522.uid.size);
        for (byte i = 0; i < mfrc522.uid.size; i++) {
        
      lcd.print(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : "");
      lcd.print(mfrc522.uid.uidByte[i], HEX);
    }


    lcd.setCursor(0, 2);
    lcd.print("1: ATTENDANCE");
    lcd.setCursor(0,3);
    lcd.print("2. HALLPASS");
    
    tone(BUZZER_PIN, 1000);
    delay(1000);
    noTone(BUZZER_PIN);    
    // char key = keypad.getKey();
    // if (key == '1') {
    //   handleAttendance();
    // } else if (key == '2') {
    //   handleHallPass();
    // } else {
    //   lcd.clear();
    // }
    //   // lcd.clear();
    //   // lcd.print("Invalid Option");
    //   // delay(1000);
    // }

    // Add a delay and then reset the system
    // delay(500);
    // resetSystem();
  }
      char key = keypad.getKey();
    if (key == '1') {
      handleAttendance();
    } else if (key == '2') {
      handleHallPass();
    } else {
      // lcd.clear();
      // lcd.print("Invalid Option");
      // delay(1000);
    }
        mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();
      // resetSystem();
}

void handleAttendance() {
  // Your attendance handling logic here
  
  // Example: Turn on the buzzer for 1 second
  tone(BUZZER_PIN, 1000);
  delay(1000);
  noTone(BUZZER_PIN);
  
  // Display relevant information on the LCD
  lcd.clear();
  lcd.print("ATTENDANCE MARKED");
  delay(1000);
  // resetSystem();
  setup();
  delay(3000);
}

void handleHallPass() {
  // Your hall pass handling logic here

  // buzzer setup
  tone(BUZZER_PIN, 1000);
  delay(1000);
  noTone(BUZZER_PIN);
      lcd.clear();
  lcd.print("HALLPASS GRANTED");
  moveServo();
  //servo setup
  // delay(500);
  // returnServo();
  // delay(500);
  
  // lcd display

  delay(1000);
  // resetSystem();
  setup();
  delay(1000);
  
}

void resetSystem() {
  lcd.clear();
  lcd.print("Scan your card");
  // Additional setup/reset steps can be added here if needed
}

void moveServo() {
  myservo.write(180);
  delay(1000);
  myservo.write(180);;
  delay(1000);
}

void returnServo() {
  myservo.write(0);
  delay(1000);
  myservo.write(0);
}

// void getDateTime() {
//   Time t = rtc.getTime();
//   Date d = rtc.getDate();
//   lcd.print(String(d.mon) + "/" + String(d.date) + "/" + String(d.year));
//   lcd.print(" " + String(t.hour) + ":" + String(t.min) + ":" + String(t.sec));

// }