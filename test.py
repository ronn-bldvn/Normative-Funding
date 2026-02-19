# file_path = r'C:\xampp\htdocs\NormativeFundingFlask\uploads\5.-UII_CLSU-NF-FORM-GH_2024.xlsx'


"""
Standalone script to test reading Excel data
Run this first to verify data extraction works before using Flask
"""

import pandas as pd

def test_read_excel(file_path):
    """Test reading the Excel file and print the extracted data"""
    
    print("Reading Excel file...")
    print("=" * 80)
    
    try:
        # Read the Excel file
        df = pd.read_excel(file_path, sheet_name='2024', header=None)
        
        print(f"✓ File loaded successfully")
        print(f"✓ Shape: {df.shape[0]} rows x {df.shape[1]} columns\n")
        
        # Extract grand total (row 91)
        grand_total_row = df.iloc[91]
        
        print("INCOME DATA EXTRACTED:")
        print("-" * 80)
        
        tuition_misc = float(grand_total_row[3]) if pd.notna(grand_total_row[3]) else 0
        miscellaneous = float(grand_total_row[4]) if pd.notna(grand_total_row[4]) else 0
        other_income = float(grand_total_row[5]) if pd.notna(grand_total_row[5]) else 0
        grand_total = float(grand_total_row[6]) if pd.notna(grand_total_row[6]) else 0
        
        print(f"Tuition & Misc. Fees:  ₱{tuition_misc:,.2f}")
        print(f"Miscellaneous:         ₱{miscellaneous:,.2f}")
        print(f"Other Income:          ₱{other_income:,.2f}")
        print(f"Grand Total Income:    ₱{grand_total:,.2f}")
        print()
        
        # Extract detailed breakdown
        print("TUITION & FEES BREAKDOWN:")
        print("-" * 80)
        
        tuition_items = []
        for idx in range(66, 91):
            row = df.iloc[idx]
            if pd.notna(row[2]) and pd.notna(row[3]):
                try:
                    value = float(row[3])
                    if value > 0:
                        item_name = str(row[2]).strip()
                        tuition_items.append((item_name, value))
                        print(f"{item_name:.<40} ₱{value:>15,.2f}")
                except (ValueError, TypeError):
                    pass
        
        print()
        print("OTHER INCOME BREAKDOWN:")
        print("-" * 80)
        
        other_items = []
        for idx in range(66, 91):
            row = df.iloc[idx]
            if pd.notna(row[2]) and pd.notna(row[5]):
                try:
                    value = float(row[5])
                    if value > 0:
                        item_name = str(row[2]).strip()
                        other_items.append((item_name, value))
                        print(f"{item_name:.<40} ₱{value:>15,.2f}")
                except (ValueError, TypeError):
                    pass
        
        print()
        print("=" * 80)
        print("✓ Data extraction successful!")
        print(f"✓ Found {len(tuition_items)} tuition items")
        print(f"✓ Found {len(other_items)} other income items")
        
        return True
        
    except FileNotFoundError:
        print("✗ Error: File not found!")
        print(f"  Looking for: {file_path}")
        return False
    except Exception as e:
        print(f"✗ Error: {e}")
        import traceback
        traceback.print_exc()
        return False

if __name__ == '__main__':
    # Update this path to match your file location
    file_path = r'C:\xampp\htdocs\NormativeFundingFlask\uploads\funds-edited.xlsx'
    
    # For testing, you can also use absolute path:
    # file_path = '/path/to/your/5_-UII_CLSU-NF-FORM-GH_2024.xlsx'
    
    test_read_excel(file_path)