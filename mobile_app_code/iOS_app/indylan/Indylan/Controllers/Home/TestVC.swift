//
//  TestVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

struct ExerciseMode {
    let id      : String
    let title   : String
}

struct ExerciseType {
    let id      : String
    let title   : String
    let total   : Int
    
    init(withJSON json: JSON) {
        id      = json["id"].stringValue
        title   = json["type_name"].stringValue
        total   = json["total"].intValue
    }
}

class TestVC: ThemeViewController {
    
    // MARK: Outlets

    @IBOutlet var txtSelectExMode: PickerTextField!
    @IBOutlet var txtSelectExType: PickerTextField!
    @IBOutlet var txtSelectNoQues: PickerTextField!

    @IBOutlet var btnNext: ThemeButton!
    @IBOutlet weak var bottomConstraint: ThemeBottomConstraint!
    
    // MARK: Class Properties
    
    private var arrExerciseModes: [ExerciseMode] = []
    private var arrExerciseTypes: [ExerciseType] = []
    private var arrQuestions: [String] = []
    
    private var exerciseModesPickerView: UIPickerView!
    private var exerciseTypesPickerView: UIPickerView!
    private var questionsPickerView: UIPickerView!
    
    private var selectedExerciseModeIndex: Int = 0
    private var selectedExerciseTypeIndex: Int = 0
    private var selectedQuestionIndex: Int = 0
    
    // MARK: Custom Functions
    
    private func setupView() {
        self.title = "test".localized()

        btnNext.setTitle("next".localized(), for: UIControlState.normal)
        
        loadExerciseModeData()
        
        setupPickerView()
        
        txtSelectExMode.placeholder = "selectExercisemode".localized()
        txtSelectExType.placeholder = "selectExerciseType".localized()
        txtSelectNoQues.placeholder = "chooseNoQues".localized()
        
        txtSelectExMode.delegate = self
        txtSelectExType.delegate = self
        txtSelectNoQues.delegate = self
        
        txtSelectExMode.inputView = exerciseModesPickerView
        txtSelectExType.inputView = exerciseTypesPickerView
        txtSelectNoQues.inputView = questionsPickerView
        
        txtSelectExType.isEnabled = false
        txtSelectNoQues.isEnabled = false
        
        btnNext.isEnabled = false
        bottomConstraint.constant = ScreenHeight * 0.13
    }
    
    private func setupPickerView() {
        exerciseModesPickerView = UIPickerView()
        exerciseModesPickerView.backgroundColor = .white
        exerciseModesPickerView.delegate = self
        exerciseModesPickerView.dataSource = self
        
        exerciseTypesPickerView = UIPickerView()
        exerciseTypesPickerView.backgroundColor = .white
        exerciseTypesPickerView.delegate = self
        exerciseTypesPickerView.dataSource = self
        
        questionsPickerView = UIPickerView()
        questionsPickerView.backgroundColor = .white
        questionsPickerView.delegate = self
        questionsPickerView.dataSource = self
    }
    
    private func loadExerciseModeData() {
        arrExerciseModes.append(ExerciseMode(id: "1", title: "vocabulary".localized()))
        arrExerciseModes.append(ExerciseMode(id: "2", title: "phrases".localized()))
        arrExerciseModes.append(ExerciseMode(id: "3", title: "dialogues".localized()))
        arrExerciseModes.append(ExerciseMode(id: "4", title: "grammar".localized()))
    }
    
    private func setQuestions() {
        
        let arrOneToTen = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
        
        let arrTenToHundred = ["10", "20", "30", "40", "50", "60", "70", "80", "90", "100"]

        let total = arrExerciseTypes[selectedExerciseTypeIndex].total
        
        if total > 99 {
            arrQuestions = arrTenToHundred
        } else {

            let x = total / 10
            
            if x < 2 {
                let total = total > 10 ? 10 : total
                
                guard total > 0 else {
                    SnackBar.show("No records found")
                    return
                }
                arrQuestions = Array(arrOneToTen.prefix(upTo: total))
            } else {
                arrQuestions = Array(arrTenToHundred.prefix(upTo: x))
            }
        }
        
        txtSelectNoQues.isEnabled = !arrQuestions.isEmpty
    }
    
    // MARK: Button Actions

    @IBAction func btnNextAction(_ sender: ThemeButton) {
        fetchExerciseSection()
    }
    
    // MARK: Web Service Functions
    
    func fetchExerciseType() {
        
        if ReachabilityManager.shared.isReachable {
            
            let dictParam = [
                "exercise_mode_id" : arrExerciseModes[selectedExerciseModeIndex].id,
            ]
            
            let strUrl = "\(Environment.APIPath)/test_exercise_type"
            
            APIManager.shared.request(strURL: strUrl, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status {
                        
                        if (resObj["status"].intValue) == 1 {
                            
                            UIView.appearance().semanticContentAttribute = .forceLeftToRight
                            
                            self.navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
                            
                            let results = resObj["result"].arrayValue
                            
                            if !results.isEmpty {
                                
                                self.arrExerciseTypes.removeAll()
                                
                                results.forEach { exerciseType in
                                    self.arrExerciseTypes.append(ExerciseType(withJSON: exerciseType))
                                }
                                
                                self.txtSelectExType.isEnabled = true
                            }
                            else {
                                SnackBar.show("No records found")
                            }
                        }
                        else {
                            SnackBar.show("\(resObj["message"])")
                        }
                    } else {
                        if resObj["message"].stringValue.count > 0 {
                            SnackBar.show("\(resObj["message"])")
                        } else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        }
        else {
            SnackBar.show("noInternet".localized())
        }
    }
    
    func fetchExerciseSection() {
        
        if ReachabilityManager.shared.isReachable {
            
            let dictParam: [String: String] = [
                "exercise_mode_id"  : arrExerciseModes[selectedExerciseModeIndex].id,
                "type"              : arrExerciseTypes[selectedExerciseTypeIndex].id,
                "question"          : arrQuestions[selectedQuestionIndex]
            ]
            
            let strUrl = "\(Environment.APIPath)/test_exercise_section"

            APIManager.shared.request(strURL: strUrl, params: dictParam, completion:
                { (status, resObj) in
                    
                    if status {
                        
                        if (resObj["status"].intValue) == 1 {
                            
                            UIView.appearance().semanticContentAttribute = .forceLeftToRight
                            
                            self.navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
                            
                            let exerciseTypeId = self.arrExerciseTypes[self.selectedExerciseTypeIndex].id
                            
                            if resObj["result"].count > 0 {

                                switch exerciseTypeId {
                                    case "1", "2", "3":
                                        
                                        let objExType1 = UIStoryboard.exercise.instantiateViewController(withClass: ExType1VC.self)!
                                        objExType1.arrType1Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType1, animated: true)
                                        
                                        break
                                    
                                    case "4", "5":
                                        
                                        let objExType4 = UIStoryboard.exercise.instantiateViewController(withClass: ExType4VC.self)!
                                        objExType4.arrType4Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType4, animated: true)
                                        
                                        break
                                    
                                    case "6":
                                        
                                        let objExType6 = UIStoryboard.exercise.instantiateViewController(withClass: ExType6VC.self)!
                                        objExType6.arrType6Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType6, animated: true)
                                        
                                        break
                                    
                                    case "7":
                                        
                                        let objExType7 = UIStoryboard.exercise.instantiateViewController(withClass: ExType7VC.self)!
                                        objExType7.arrType7Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType7, animated: true)
                                        
                                        break
                                    
                                    case "8":
                                        
                                        let objExType8 = UIStoryboard.exercise.instantiateViewController(withClass: ExType8VC.self)!
                                        objExType8.arrType8Questions = resObj["result"].arrayValue
                                        
                                        self.navigationController?.pushViewController(objExType8, animated: true)
                                        
                                        break
                                    
                                    case "9":
                                        
                                        let objExType4 = UIStoryboard.exercise.instantiateViewController(withClass: ExType4VC.self)!
                                        objExType4.arrType4Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType4, animated: true)
                                        
                                        break
                                    
                                    case "10":
                                        
                                        let objExType10 = UIStoryboard.exercise.instantiateViewController(withClass: ExType10VC.self)!
                                        objExType10.arrType10Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType10, animated: true)
                                        
                                        break
                                    
                                    case "11":
                                        
                                        let objExType11 = UIStoryboard.exercise.instantiateViewController(withClass: ExType11VC.self)!
                                        objExType11.arrType11Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType11, animated: true)
                                    
                                    case "12":
                                        
                                        let objExType12 = UIStoryboard.exercise.instantiateViewController(withClass: ExType12VC.self)!
                                        objExType12.arrType12Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType12, animated: true)
                                        
                                        break
                                    
                                    case "13", "14":
                                        
                                        let objExType13 = UIStoryboard.exercise.instantiateViewController(withClass: ExType13VC.self)!
                                        objExType13.arrType13Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType13, animated: true)
                                        
                                        break
                                    
                                    case "15":
                                        
                                        let objExType15 = UIStoryboard.exercise.instantiateViewController(withClass: ExType15VC.self)!
                                        arrType15Questions = resObj["result"].arrayValue
                                        self.navigationController?.pushViewController(objExType15, animated: true)
                                        break
                                    
                                    default:
                                        SnackBar.show("Some unexpected error has occured")
                                        
                                    break
                                }
                            } else {
                                SnackBar.show(resObj["message"].stringValue)
                            }
                        }
                        else {
                            SnackBar.show("\(resObj["message"])")
                        }
                    } else {
                        if resObj["message"].stringValue.count > 0 {
                            SnackBar.show("\(resObj["message"])")
                        } else {
                            SnackBar.show("serverTimeout".localized())
                        }
                    }
            })
        }
        else {
            SnackBar.show("noInternet".localized())
        }
    }
    
    // MARK: - Life Cycle Functions
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setupView()
    }

//    override func viewWillAppear(_ animated: Bool) {
//        super.viewWillAppear(animated)
//        
//        if selectedMenuLanguageId == "16" || selectedMenuLanguageId == "23" || selectedMenuLanguageId == "24" || selectedMenuLanguageId == "27" {
//            UIView.appearance().semanticContentAttribute = .forceRightToLeft
//            navigationController?.navigationBar.semanticContentAttribute = .forceRightToLeft
//        } else {
//            UIView.appearance().semanticContentAttribute = .forceLeftToRight
//            navigationController?.navigationBar.semanticContentAttribute = .forceLeftToRight
//        }
//    }

}

// MARK: - UIPickerView Delegates & Data Source -

extension TestVC: UIPickerViewDelegate, UIPickerViewDataSource {
    
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        if pickerView == exerciseModesPickerView {
            return arrExerciseModes.count
        } else if pickerView == exerciseTypesPickerView {
            return arrExerciseTypes.count
        } else if pickerView == questionsPickerView {
            return arrQuestions.count
        }
        
        return 0
    }
    
    func pickerView(_ pickerView: UIPickerView, viewForRow row: Int, forComponent component: Int, reusing view: UIView?) -> UIView {
        var label = UILabel()
        if let v = view as? UILabel { label = v }
        label.font = Fonts.centuryGothic(ofType: .bold, withSize: 16)
        label.textAlignment = .center
         
        if pickerView == exerciseModesPickerView {
            label.text = arrExerciseModes[row].title
        } else if pickerView == exerciseTypesPickerView {
            label.text = arrExerciseTypes[row].title
        } else if pickerView == questionsPickerView {
            label.text = arrQuestions[row]
        }

        return label
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        if pickerView == exerciseModesPickerView {
            txtSelectExMode.text = arrExerciseModes[row].title
            selectedExerciseModeIndex = row
        } else if pickerView == exerciseTypesPickerView {
            txtSelectExType.text = arrExerciseTypes[row].title
            selectedExerciseTypeIndex = row
            selectedExTypeId = arrExerciseTypes[row].id
        } else if pickerView == questionsPickerView {
            txtSelectNoQues.text = arrQuestions[row]
            selectedQuestionIndex = row
        }
    }
}

// MARK: - UITextField Delegate -

extension TestVC: UITextFieldDelegate {

    func textFieldDidBeginEditing(_ textField: UITextField) {
        
        if textField == txtSelectExMode {
            
            txtSelectExMode.text = arrExerciseModes[selectedExerciseModeIndex].title
            exerciseModesPickerView.selectRow(selectedExerciseModeIndex, inComponent: 0, animated: true)
            
            selectedExTypeId = ""
            selectedExerciseTypeIndex = 0
            txtSelectExType.isEnabled = false
            txtSelectExType.text = ""
            
            selectedQuestionIndex = 0
            txtSelectNoQues.isEnabled = false
            txtSelectNoQues.text = ""
            
            btnNext.isEnabled = false
            
        } else if textField == txtSelectExType {
            
            txtSelectExType.text = arrExerciseTypes[selectedExerciseTypeIndex].title
            exerciseTypesPickerView.selectRow(selectedExerciseTypeIndex, inComponent: 0, animated: true)
            
            selectedQuestionIndex = 0
            txtSelectNoQues.isEnabled = false
            txtSelectNoQues.text = ""
            
            btnNext.isEnabled = false
            
        } else if textField == txtSelectNoQues {
            
            txtSelectNoQues.text = arrQuestions[selectedQuestionIndex]
            questionsPickerView.selectRow(selectedQuestionIndex, inComponent: 0, animated: true)
        }
    }
    
    func textFieldDidEndEditing(_ textField: UITextField) {
        if textField == txtSelectExMode {
            fetchExerciseType()
        } else if textField == txtSelectExType {
            setQuestions()
        } else if textField == txtSelectNoQues {
            btnNext.isEnabled = true
        }
    }
}
