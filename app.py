from flask import Flask, render_template, jsonify, request
import pandas as pd
import os

app = Flask(__name__)

# Configure upload folder
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

#################################################

# FOR NORMATIVE FUNDING BREAKDOWN 

def get_latest_year_sheet(file_path):
    xls = pd.ExcelFile(file_path)
    year_sheets = [int(s) for s in xls.sheet_names if s.isdigit()]
    return str(max(year_sheets))

def read_allotment_data(file_path, sheet_name):
    """
    Read allotment data from the CLSU Excel file
    Returns a dictionary with allotment data for both GAA and SUC Income
    """
    try:
        df = pd.read_excel(file_path, sheet_name=sheet_name, header=None)
        
        allotment_data = {
            'gaa': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'suc_income': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'combined': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'breakdown': []
        }
        
        # Row 21 contains TOTAL ALLOTMENTS
        total_row = df.iloc[21]
        
        # GAA (Fund 101) - Columns 3, 4, 5, 6
        if pd.notna(total_row[3]):
            allotment_data['gaa']['ps'] = float(total_row[3])
        if pd.notna(total_row[4]):
            allotment_data['gaa']['mooe'] = float(total_row[4])
        if pd.notna(total_row[5]):
            allotment_data['gaa']['co'] = float(total_row[5])
        if pd.notna(total_row[6]):
            allotment_data['gaa']['total'] = float(total_row[6])
        
        # SUC Income (Fund 164) - Columns 8, 9, 10, 11
        if pd.notna(total_row[8]):
            allotment_data['suc_income']['ps'] = float(total_row[8])
        if pd.notna(total_row[9]):
            allotment_data['suc_income']['mooe'] = float(total_row[9])
        if pd.notna(total_row[10]):
            allotment_data['suc_income']['co'] = float(total_row[10])
        if pd.notna(total_row[11]):
            allotment_data['suc_income']['total'] = float(total_row[11])
        
        # Combined - Columns 13, 14, 15, 16
        if pd.notna(total_row[13]):
            allotment_data['combined']['ps'] = float(total_row[13])
        if pd.notna(total_row[14]):
            allotment_data['combined']['mooe'] = float(total_row[14])
        if pd.notna(total_row[15]):
            allotment_data['combined']['co'] = float(total_row[15])
        if pd.notna(total_row[16]):
            allotment_data['combined']['total'] = float(total_row[16])
        
        # Get breakdown by function (rows 11-18)
        for idx in range(11, 19):
            row = df.iloc[idx]
            
            # Check if this is a main function (rows with numeric ID in column 0)
            if pd.notna(row[0]) and isinstance(row[0], (int, float)):
                # If it's item 5 (OTHERS), skip it - we'll use sub-items instead
                if int(row[0]) == 5:
                    continue
                    
                function_name = str(row[1]).strip()
                allotment_data['breakdown'].append({
                    'function': function_name,
                    'gaa_ps': float(row[3]) if pd.notna(row[3]) else 0,
                    'gaa_mooe': float(row[4]) if pd.notna(row[4]) else 0,
                    'gaa_co': float(row[5]) if pd.notna(row[5]) else 0,
                    'gaa_total': float(row[6]) if pd.notna(row[6]) else 0,
                    'suc_ps': float(row[8]) if pd.notna(row[8]) else 0,
                    'suc_mooe': float(row[9]) if pd.notna(row[9]) else 0,
                    'suc_co': float(row[10]) if pd.notna(row[10]) else 0,
                    'suc_total': float(row[11]) if pd.notna(row[11]) else 0,
                })
            # Check if this is a sub-item (5.1, 5.2, 5.3 in column 1)
            elif pd.notna(row[1]) and pd.notna(row[2]):
                # Check if column 1 is a float like 5.1, 5.2, 5.3
                try:
                    sub_id = float(row[1])
                    if sub_id in [5.1, 5.2, 5.3]:
                        function_name = str(row[2]).strip()
                        allotment_data['breakdown'].append({
                            'function': function_name,
                            'gaa_ps': float(row[3]) if pd.notna(row[3]) else 0,
                            'gaa_mooe': float(row[4]) if pd.notna(row[4]) else 0,
                            'gaa_co': float(row[5]) if pd.notna(row[5]) else 0,
                            'gaa_total': float(row[6]) if pd.notna(row[6]) else 0,
                            'suc_ps': float(row[8]) if pd.notna(row[8]) else 0,
                            'suc_mooe': float(row[9]) if pd.notna(row[9]) else 0,
                            'suc_co': float(row[10]) if pd.notna(row[10]) else 0,
                            'suc_total': float(row[11]) if pd.notna(row[11]) else 0,
                        })
                except (ValueError, TypeError):
                    pass
        
        return allotment_data
        
    except Exception as e:
        print(f"Error reading allotment data: {e}")
        import traceback
        traceback.print_exc()
        return {
            'gaa': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'suc_income': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'combined': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'breakdown': []
        }

def read_expenditure_data(file_path, sheet_name):
    """
    Read expenditure data from the CLSU Excel file
    Returns a dictionary with expenditure data for both GAA and SUC Income
    """
    try:
        df = pd.read_excel(file_path, sheet_name=sheet_name, header=None)
        
        expenditure_data = {
            'gaa': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'suc_income': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'combined': {
                'ps': 0,
                'mooe': 0,
                'co': 0,
                'total': 0
            },
            'breakdown': []
        }
        
        # Row 49 contains TOTAL EXPENDITURES
        total_row = df.iloc[49]
        
        # GAA (Fund 101) - Columns 3, 4, 5, 6
        if pd.notna(total_row[3]):
            expenditure_data['gaa']['ps'] = float(total_row[3])
        if pd.notna(total_row[4]):
            expenditure_data['gaa']['mooe'] = float(total_row[4])
        if pd.notna(total_row[5]):
            expenditure_data['gaa']['co'] = float(total_row[5])
        if pd.notna(total_row[6]):
            expenditure_data['gaa']['total'] = float(total_row[6])
        
        # SUC Income (Fund 164) - Columns 8, 9, 10, 11
        if pd.notna(total_row[8]):
            expenditure_data['suc_income']['ps'] = float(total_row[8])
        if pd.notna(total_row[9]):
            expenditure_data['suc_income']['mooe'] = float(total_row[9])
        if pd.notna(total_row[10]):
            expenditure_data['suc_income']['co'] = float(total_row[10])
        if pd.notna(total_row[11]):
            expenditure_data['suc_income']['total'] = float(total_row[11])
        
        # Combined - Columns 13, 14, 15, 16
        if pd.notna(total_row[13]):
            expenditure_data['combined']['ps'] = float(total_row[13])
        if pd.notna(total_row[14]):
            expenditure_data['combined']['mooe'] = float(total_row[14])
        if pd.notna(total_row[15]):
            expenditure_data['combined']['co'] = float(total_row[15])
        if pd.notna(total_row[16]):
            expenditure_data['combined']['total'] = float(total_row[16])
        
        # Get breakdown by function (rows 39-46)
        for idx in range(39, 47):
            row = df.iloc[idx]
            
            # Check if this is a main function (rows with numeric ID in column 0)
            if pd.notna(row[0]) and isinstance(row[0], (int, float)):
                # If it's item 5 (OTHERS), skip it - we'll use sub-items instead
                if int(row[0]) == 5:
                    continue
                    
                function_name = str(row[1]).strip()
                expenditure_data['breakdown'].append({
                    'function': function_name,
                    'gaa_ps': float(row[3]) if pd.notna(row[3]) else 0,
                    'gaa_mooe': float(row[4]) if pd.notna(row[4]) else 0,
                    'gaa_co': float(row[5]) if pd.notna(row[5]) else 0,
                    'gaa_total': float(row[6]) if pd.notna(row[6]) else 0,
                    'suc_ps': float(row[8]) if pd.notna(row[8]) else 0,
                    'suc_mooe': float(row[9]) if pd.notna(row[9]) else 0,
                    'suc_co': float(row[10]) if pd.notna(row[10]) else 0,
                    'suc_total': float(row[11]) if pd.notna(row[11]) else 0,
                })
            # Check if this is a sub-item (5.1, 5.2, 5.3 in column 1)
            elif pd.notna(row[1]) and pd.notna(row[2]):
                # Check if column 1 is a float like 5.1, 5.2, 5.3
                try:
                    sub_id = float(row[1])
                    if sub_id in [5.1, 5.2, 5.3]:
                        function_name = str(row[2]).strip()
                        expenditure_data['breakdown'].append({
                            'function': function_name,
                            'gaa_ps': float(row[3]) if pd.notna(row[3]) else 0,
                            'gaa_mooe': float(row[4]) if pd.notna(row[4]) else 0,
                            'gaa_co': float(row[5]) if pd.notna(row[5]) else 0,
                            'gaa_total': float(row[6]) if pd.notna(row[6]) else 0,
                            'suc_ps': float(row[8]) if pd.notna(row[8]) else 0,
                            'suc_mooe': float(row[9]) if pd.notna(row[9]) else 0,
                            'suc_co': float(row[10]) if pd.notna(row[10]) else 0,
                            'suc_total': float(row[11]) if pd.notna(row[11]) else 0,
                        })
                except (ValueError, TypeError):
                    pass
        
        return expenditure_data
        
    except Exception as e:
        print(f"Error reading expenditure data: {e}")
        import traceback
        traceback.print_exc()
        return {
            'gaa': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'suc_income': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'combined': {'ps': 0, 'mooe': 0, 'co': 0, 'total': 0},
            'breakdown': []
        }

def read_income_data(file_path, sheet_name):
    """
    Read income data from the CLSU Excel file
    Returns a dictionary with financial data
    """
    try:
        # Read the Excel file (2024 sheet)
        df = pd.read_excel(file_path, sheet_name=sheet_name, header=None)
        
        # Initialize income data structure
        income_data = {
            'grand_total_income': 0,
            'tuition_misc_fee': 0,
            'miscellaneous': 0,
            'other_income': 0,
            'breakdown': {
                'main_categories': [],
                'tuition_details': [],
                'other_income_details': []
            }
        }
        
        # Extract data from row 91 (GRAND TOTAL FOR SUC)
        grand_total_row = df.iloc[91]
        
        # Column 3: Tuition and Misc Fees
        if pd.notna(grand_total_row[3]):
            income_data['tuition_misc_fee'] = float(grand_total_row[3])
        
        # Column 4: Miscellaneous
        if pd.notna(grand_total_row[4]):
            income_data['miscellaneous'] = float(grand_total_row[4])
        
        # Column 5: Other Income
        if pd.notna(grand_total_row[5]):
            income_data['other_income'] = float(grand_total_row[5])
        
        # Column 6: Grand Total
        if pd.notna(grand_total_row[6]):
            income_data['grand_total_income'] = float(grand_total_row[6])
        
        # Extract detailed breakdown for charts
        # Rows 66-88 contain the income breakdown
        tuition_items = []
        other_income_items = []
        
        for idx in range(66, 91):
            row = df.iloc[idx]
            
            if pd.notna(row[2]):  # Item name in column 2
                item_name = str(row[2]).strip()
                
                # Tuition and fees (column 3)
                if pd.notna(row[3]):
                    try:
                        value = float(row[3])
                        if value > 0:
                            tuition_items.append({
                                'name': item_name,
                                'value': value
                            })
                    except (ValueError, TypeError):
                        pass
                
                # Other income (column 5)
                if pd.notna(row[5]):
                    try:
                        value = float(row[5])
                        if value > 0:
                            other_income_items.append({
                                'name': item_name,
                                'value': value
                            })
                    except (ValueError, TypeError):
                        pass
        
        # Main categories for the first chart
        income_data['breakdown']['main_categories'] = [
            {'name': 'Tuition & Misc. Fees', 'value': income_data['tuition_misc_fee']},
            {'name': 'Miscellaneous', 'value': income_data['miscellaneous']},
            {'name': 'Other Income', 'value': income_data['other_income']}
        ]
        
        income_data['breakdown']['tuition_details'] = tuition_items
        income_data['breakdown']['other_income_details'] = other_income_items
        
        return income_data
        
    except Exception as e:
        print(f"Error reading Excel file: {e}")
        import traceback
        traceback.print_exc()
        return {
            'grand_total_income': 0,
            'tuition_misc_fee': 0,
            'miscellaneous': 0,
            'other_income': 0,
            'breakdown': {
                'main_categories': [],
                'tuition_details': [],
                'other_income_details': []
            }
        }

def get_total_income_trend(excel_path):
    xls = pd.ExcelFile(excel_path)
    yearly_totals = {}

    for sheet in xls.sheet_names:
        if not sheet.isdigit():
            continue

        df = pd.read_excel(excel_path, sheet_name=sheet, header=None)

        matches = df[df.apply(
            lambda r: r.astype(str).str.contains("TOTAL SUC INCOME", case=False).any(),
            axis=1
        )]

        if not matches.empty:
            yearly_totals[sheet] = float(matches.iloc[0, -1])

    return yearly_totals

#################################################

#################################################

# FOR SUC FACULTY

# def read_suc_faculty(file_path):

#################################################

#################################################

# FOR GRADUATES

#################################################

#################################################

# ROUTES FOR PAGEES

#################################################

# ROUTE FOR NORMATIVE FUNDNG BREAKDOWN 
@app.route('/')
def index():
    excel_file = os.path.join(app.config['UPLOAD_FOLDER'], 'funds.xlsx')
    
    # 1. Get ALL sheet names that look like years
    xls = pd.ExcelFile(excel_file)
    all_years = sorted([s for s in xls.sheet_names if s.isdigit()], reverse=True)

    # 2. Check if the user selected a year from the dropdown
    selected_year = request.args.get('year')
    if not selected_year or selected_year not in all_years:
        selected_year = all_years[0] if all_years else "None"

    # 3. Get the filter type (default to 'all')
    filter_type = request.args.get('type', 'all')

    # 4. Fetch data based on filter type
    income_data = read_income_data(excel_file, selected_year)
    allotment_data = read_allotment_data(excel_file, selected_year)
    expenditure_data = read_expenditure_data(excel_file, selected_year)

    # Format income data
    formatted_income = {
        'grand_total_income': f"₱{income_data['grand_total_income']:,.2f}",
        'tuition_misc_fee': f"₱{income_data['tuition_misc_fee']:,.2f}",
        'miscellaneous': f"₱{income_data['miscellaneous']:,.2f}",
        'other_income': f"₱{income_data['other_income']:,.2f}"
    }

    # Format allotment data
    formatted_allotment = {
        'gaa_total': f"₱{allotment_data['gaa']['total']:,.2f}",
        'gaa_ps': f"₱{allotment_data['gaa']['ps']:,.2f}",
        'gaa_mooe': f"₱{allotment_data['gaa']['mooe']:,.2f}",
        'gaa_co': f"₱{allotment_data['gaa']['co']:,.2f}",
        'suc_total': f"₱{allotment_data['suc_income']['total']:,.2f}",
        'suc_ps': f"₱{allotment_data['suc_income']['ps']:,.2f}",
        'suc_mooe': f"₱{allotment_data['suc_income']['mooe']:,.2f}",
        'suc_co': f"₱{allotment_data['suc_income']['co']:,.2f}",
        'combined_total': f"₱{allotment_data['combined']['total']:,.2f}",
    }

    # Format expenditure data
    formatted_expenditure = {
        'gaa_total': f"₱{expenditure_data['gaa']['total']:,.2f}",
        'gaa_ps': f"₱{expenditure_data['gaa']['ps']:,.2f}",
        'gaa_mooe': f"₱{expenditure_data['gaa']['mooe']:,.2f}",
        'gaa_co': f"₱{expenditure_data['gaa']['co']:,.2f}",
        'suc_total': f"₱{expenditure_data['suc_income']['total']:,.2f}",
        'suc_ps': f"₱{expenditure_data['suc_income']['ps']:,.2f}",
        'suc_mooe': f"₱{expenditure_data['suc_income']['mooe']:,.2f}",
        'suc_co': f"₱{expenditure_data['suc_income']['co']:,.2f}",
        'combined_total': f"₱{expenditure_data['combined']['total']:,.2f}",
    }

    # Data for your trend chart
    suc_trend = get_total_income_trend(excel_file)
    chart_years = sorted(suc_trend.keys())
    chart_totals = [suc_trend[y] for y in chart_years]

    return render_template(
        'dashboard.html',
        income=formatted_income,
        allotment=formatted_allotment,
        expenditure=formatted_expenditure,
        year=selected_year,
        filter_type=filter_type,
        suc_years=all_years,          
        suc_totals=chart_totals,      
        suc_years_chart=chart_years,  
        active_page='normative_breakdown' 
    )

@app.route('/api/income-data')
def get_income_data():
    excel_file = os.path.join(app.config['UPLOAD_FOLDER'], 'funds.xlsx')

    selected_year = request.args.get('year')
    xls = pd.ExcelFile(excel_file)
    valid_years = [s for s in xls.sheet_names if s.isdigit()]

    if not selected_year or selected_year not in valid_years:
        selected_year = max(valid_years)

    income_data = read_income_data(excel_file, selected_year)
    income_data['year'] = selected_year

    return jsonify(income_data)

@app.route('/api/allotment-data')
def get_allotment_data():
    excel_file = os.path.join(app.config['UPLOAD_FOLDER'], 'funds.xlsx')

    selected_year = request.args.get('year')
    xls = pd.ExcelFile(excel_file)
    valid_years = [s for s in xls.sheet_names if s.isdigit()]

    if not selected_year or selected_year not in valid_years:
        selected_year = max(valid_years)

    allotment_data = read_allotment_data(excel_file, selected_year)
    allotment_data['year'] = selected_year

    return jsonify(allotment_data)

@app.route('/api/expenditure-data')
def get_expenditure_data():
    excel_file = os.path.join(app.config['UPLOAD_FOLDER'], 'funds.xlsx')

    selected_year = request.args.get('year')
    xls = pd.ExcelFile(excel_file)
    valid_years = [s for s in xls.sheet_names if s.isdigit()]

    if not selected_year or selected_year not in valid_years:
        selected_year = max(valid_years)

    expenditure_data = read_expenditure_data(excel_file, selected_year)
    expenditure_data['year'] = selected_year

    return jsonify(expenditure_data)

#################################################

#################################################

# ROUTES FOR SUC-FACULTY

@app.route('/suc-faculty')
def suc_faculty():
        return render_template(
        'suc-faculty.html',
        active_page='suc-faculty'
    )

#################################################


#################################################

# ROUTES FOR GRADUATES

@app.route('/graduates')
def graduates():  
    return render_template(
        'graduates.html',
        active_page='graduates'
    )
    

#################################################

if __name__ == '__main__':
    app.run(debug=True)