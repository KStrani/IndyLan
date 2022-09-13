//
//  ExType4VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit
import AVFoundation

// MARK: - Custom Class Declarations

class CellAnswers: UICollectionViewCell {
    
    @IBOutlet var tfAnswer: UITextField!
    
    @IBOutlet weak var vwBottom: UIView!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        vwBottom.backgroundColor = Colors.darkGray
        
        tfAnswer.tintColor = .clear
        tfAnswer.textColor = Colors.black
        tfAnswer.font = Fonts.centuryGothic(ofType: .bold, withSize: 18)
    }
}

class CellOptions: UICollectionViewCell {
    @IBOutlet weak var vwContainer: CardView!
    
    @IBOutlet weak var lblTitle: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        vwContainer.layer.borderWidth = themeBorderWidth
        vwContainer.layer.borderColor = Colors.border.cgColor
        
        lblTitle.textColor = Colors.black
        lblTitle.font = Fonts.centuryGothic(ofType: .bold, withSize: 18)
    }
}

// -----------------------------------------------------------------------------------------------

class ExType4VC: ExerciseController, AVAudioPlayerDelegate {
    
    //MARK: DECLARATION
    
    @IBOutlet var scrView: BaseScrollView!
    
    @IBOutlet var imgViewQuestion: UIImageView!
    
    @IBOutlet var btnAudio: UIButton!
    
    @IBOutlet var btnAudioType9: UIButton!
    
    @IBOutlet var cvAnswer: DynamicCollectionView!
    
    @IBOutlet var cvOptions: DynamicCollectionView!
    
    @IBOutlet var btnContinue: UIButton!
    
    @IBOutlet weak var btnShowAns : UIButton!
    
    private var numberOfTextFieldInRow: CGFloat = 7
    private var numberOfAnswerInRow: CGFloat = 4
    
    var bombSoundEffect: AVAudioPlayer?
    
    var audioPlayer: AVPlayer!
    
    var indicator : UIActivityIndicatorView!
    
    var playerItem: AVPlayerItem!
    
    var arrType4Questions = Array<JSON>()
    
    var strUrl = "" as String
    
    var optionsDict = [:] as NSDictionary
    
    var questionIndex = 0
    
    var score = 0
    
    var attempts = 0
    
    var wrongAttempts = 0
    
    var strWord = ""
    
    var strSelectedWord = ""
    
    var wrongIndex = 123
    
    var arrOptions = [] as NSMutableArray
    
    var isAlertDisplayed = false
    
    var isUIUpdateRequired = true
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.title = selectedCategory
        
        imgViewQuestion.layer.cornerRadius = 8
        
//        btnAudioType9.isHidden = true
//        btnAudio.isHidden = true
        
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        btnContinue.layer.cornerRadius = 5
        
        btnContinue.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnContinue.layer.borderWidth = 1
        btnContinue.layer.borderColor = Colors.border.cgColor
        view.bringSubview(toFront: btnContinue)
        
        if (arrType4Questions[questionIndex]["option"].exists())
        {
            btnContinue.setTitle("chooseOption".localized(), for: .normal)
        }
        else
        {
            btnContinue.setTitle("typeAnswer".localized(), for: .normal)
        }
        
        self.automaticallyAdjustsScrollViewInsets =  false
        
        if (UserDefaults.standard.object(forKey: "type4KeyboardAlert")) != nil {
            isAlertDisplayed = true
        }
        
        self.updateQuestion()
    }

    func updateQuestion() {
        if audioPlayer != nil {
            indicator.stopAnimating()
            
            audioPlayer.pause()
        }
        
        self.view.isUserInteractionEnabled = true
        
        attempts = 0
        
        wrongAttempts = 0
        
        strSelectedWord = ""
        
        wrongIndex = 123
        
        strWord = arrType4Questions[questionIndex]["word"].stringValue
        
        arrOptions.removeAllObjects()
        
        if (arrType4Questions[questionIndex]["option"].exists())
        {
            btnContinue.setTitle("chooseOption".localized(), for: .normal)
            
            var chars = Array(strWord)
            cvOptions.isHidden = false
            
            while chars.count > 0
            {
                let index = Int(arc4random_uniform(UInt32(chars.count - 1)))
                let Dict = ["word" : chars[index], "isSelected" : 0] as [String : Any]
                arrOptions.add(Dict)
                chars.remove(at: index)
            }
        }
        else
        {
            btnContinue.setTitle("typeAnswer".localized(), for: .normal)
            cvOptions.isHidden = true
        }
        
        btnContinue.setTitleColor(Colors.black, for: .normal)
        btnContinue.backgroundColor = Colors.white
        btnContinue.isUserInteractionEnabled = false
        
        if arrType4Questions[questionIndex]["image_path"].exists()
        {
            btnAudioType9.isHidden = true
            
            if (arrType4Questions[questionIndex]["audio_file"].stringValue.isEmpty)
            {
//                btnAudio.isHidden = true
            }
            else
            {
                btnAudio.isHidden = false
                strUrl = arrType4Questions[questionIndex]["audio_file"].stringValue
            }
            
            imgViewQuestion.setImage(withURL: arrType4Questions[questionIndex]["image_path"].stringValue)
        }
        else
        {
//            btnAudio.isHidden = true
            
            if (arrType4Questions[questionIndex]["audio_file"].stringValue.isEmpty)
            {
                btnAudioType9.isHidden = true
            }
            else
            {
//                btnAudioType9.isHidden = true
                btnAudio.isHidden = false
                strUrl = arrType4Questions[questionIndex]["audio_file"].stringValue
            }
            
            btnAudio.setImage(UIImage(named: "audio_icon_9"), for: .normal)
            imgViewQuestion.isHidden = true
        }
        
        cvOptions.reloadData()
        cvAnswer.reloadData()
        
        self.view.layoutIfNeeded()
        
        if !(arrType4Questions[questionIndex]["option"].exists())
        {
            let cell = self.cvAnswer.cellForItem(at: IndexPath(item: 0, section: 0)) as! CellAnswers
            
            cell.tfAnswer.becomeFirstResponder()
        }
    }
    
    // MARK: Button Pressed Actions
    
    @IBAction func btnAudioType9Pressed(_ sender: UIButton) {
        self.btnAudioPressed(btnAudioType9)
    }
    
    @IBAction func btnAudioPressed(_ sender: UIButton) {
        
        let url =  URL(string: strUrl as String)
        
        if (url != nil) {
            
            if audioPlayer != nil {
                audioPlayer.removeObserver(self, forKeyPath: "status")
            }
            
            playerItem = AVPlayerItem(url: url!)
            
            indicator = UIActivityIndicatorView(activityIndicatorStyle: .whiteLarge)
            
            if sender == btnAudioType9 {
                indicator.center = btnAudioType9.center
            } else {
                indicator.center = btnAudio.center
            }
            
            scrView.addSubview(indicator)
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.0) {
                self.btnAudio.isUserInteractionEnabled = false
                self.indicator.startAnimating()
            }
            
            audioPlayer = AVPlayer(playerItem:playerItem)
            audioPlayer.addObserver( self, forKeyPath:"status", options:.initial, context:nil)
            audioPlayer.play()
        }
        else
        {
            SnackBar.show("Audio error")
        }
    }
    
    override func observeValue(forKeyPath keyPath: String?, of object: Any?, change: [NSKeyValueChangeKey : Any]?, context: UnsafeMutableRawPointer?) {
        if keyPath == "status" {
            indicator.stopAnimating()
            btnAudio.isUserInteractionEnabled = true
        }
    }
    
    @IBAction func btnTypeAnsClicked(_ sender: Any) {
        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnContinue.setTitleColor(Colors.white, for: .normal)
            self.btnContinue.setTitle(self.strWord, for: .normal)
            self.btnContinue.backgroundColor = Colors.green
        }, completion: nil)
    }
    @IBAction func btnShowAnsTap(){
        UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnShowAns.setTitleColor(Colors.white, for: .normal)
            self.btnShowAns.titleLabel?.lineBreakMode = .byWordWrapping
            self.btnShowAns.titleLabel?.numberOfLines = 2
            self.btnShowAns.titleLabel?.textAlignment = .center
            self.btnShowAns.setTitle(self.strWord, for: .normal)
            self.btnShowAns.backgroundColor = Colors.green
        }, completion: nil)
    }
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        if audioPlayer != nil {
            audioPlayer.removeObserver(self, forKeyPath: "status")
        }
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}

extension ExType4VC: UICollectionViewDataSource, UICollectionViewDelegate, UICollectionViewDelegateFlowLayout {
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if collectionView == cvAnswer {
            return strWord.count
        } else if collectionView == cvOptions {
            return arrOptions.count
        } else {
            return 0
        }
    }
    
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, sizeForItemAt indexPath: IndexPath) -> CGSize {
        
        if collectionView == cvAnswer {
            let ratio = collectionView.frame.width / numberOfTextFieldInRow
            return CGSize(width: ratio, height: ratio)
        } else if collectionView == cvOptions {
            let ratio = collectionView.frame.width / numberOfAnswerInRow
            return CGSize(width: ratio, height: ratio)
        } else {
            return CGSize.zero
        }
    }

    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, insetForSectionAt section: Int) -> UIEdgeInsets {
        
        if collectionView == cvAnswer {
            
            let totalItems = CGFloat(strWord.count)
            
            if totalItems < numberOfTextFieldInRow {
                
                let ratio = collectionView.frame.width / numberOfTextFieldInRow
                let leftPadding = (collectionView.frame.width - (ratio * totalItems)) / 2
                
                return UIEdgeInsets(top: 0, left: leftPadding, bottom: 0, right: leftPadding)
            }
            
        } else if collectionView == cvOptions {
            let totalItems = CGFloat(arrOptions.count)
            
            if totalItems < numberOfAnswerInRow {
                
                let ratio = collectionView.frame.width / numberOfAnswerInRow
                let leftPadding = (collectionView.frame.width - (ratio * totalItems)) / 2
                
                return UIEdgeInsets(top: 0, left: leftPadding, bottom: 0, right: leftPadding)
            }
        }
        
        return UIEdgeInsets.zero
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        
        if arrOptions.count > 0 {
            optionsDict = arrOptions[indexPath.item] as! NSDictionary
        }
        
        if collectionView == cvAnswer {
            
            let cell = cvAnswer.dequeueReusableCell(withReuseIdentifier: "cellAnswer", for: indexPath) as! CellAnswers
            
            cell.tfAnswer.autocorrectionType = UITextAutocorrectionType.no
            
            if #available(iOS 11.0, *) {
                cell.tfAnswer.smartQuotesType = .no
            }
            
            if arrOptions.count > 0 {
                
                cell.tfAnswer.isUserInteractionEnabled = false
                
                if strSelectedWord.count > indexPath.item {
                    cell.tfAnswer.text = String((strSelectedWord[strSelectedWord.index(strSelectedWord.startIndex, offsetBy: indexPath.item)]))
                } else {
                    cell.tfAnswer.text = ""
                }
            }
            else {
                cell.tfAnswer.text = ""
                cell.tfAnswer.isUserInteractionEnabled = true
                cell.tfAnswer.delegate = self
                cell.tfAnswer.tag = indexPath.item
                cell.tfAnswer.addTarget(self, action: #selector(textDidChanged(textField:)), for: .editingChanged)
            }
            
            return cell
        }
        else
        {
            let cellOptions = cvOptions.dequeueReusableCell(withReuseIdentifier: "cellOptions", for: indexPath) as! CellOptions
            
            cellOptions.lblTitle.text = String(optionsDict["word"] as! Character)
            
            if indexPath.row == wrongIndex
            {
                cellOptions.shake(pixel: 8)
                cellOptions.vwContainer.backgroundColor = Colors.red.withAlphaComponent(0.15)
                cellOptions.vwContainer.layer.borderColor = Colors.red.cgColor
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    
                    UIView.animate(withDuration: 0.3) {
                        cellOptions.vwContainer.backgroundColor = Colors.white
                    }
                    
                    cellOptions.vwContainer.animateBorderColor(toColor: Colors.border, duration: 0.3)
                }
            }
            else
            {
                cellOptions.vwContainer.backgroundColor = Colors.white
                cellOptions.vwContainer.layer.borderColor = Colors.border.cgColor
            }
            
            if (optionsDict["isSelected"] as! Int) == 1
            {
                cellOptions.isUserInteractionEnabled = false
                cellOptions.vwContainer.isHidden = true
            }
            else
            {
                cellOptions.isUserInteractionEnabled = true
                cellOptions.vwContainer.isHidden = false
            }
            
            return cellOptions
        }
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        
        wrongIndex = 123
        
        isUIUpdateRequired = false
        
        if collectionView == cvOptions {
            attempts += 1
            
            let dict = arrOptions[indexPath.item] as! NSDictionary
            
            if (String(dict["word"] as! Character)) == (String((strWord[strWord.index(strWord.startIndex, offsetBy: strSelectedWord.count)]))) {
                
                strSelectedWord = strSelectedWord.appending(String(dict["word"] as! Character))
                
                let newDict = ["word" : dict["word"]!, "isSelected" : 1] as [String : Any]
                
                arrOptions.removeObject(at: indexPath.item)
                arrOptions.insert(newDict, at: indexPath.item)
                cvAnswer.reloadData()
                cvOptions.reloadData()
            }
            else
            {
                wrongAttempts += 1
                
                TapticEngine.notification.feedback(.error)
                
                if wrongAttempts == 5 {
                    btnContinue.isUserInteractionEnabled = true
                }
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnContinue.backgroundColor = Colors.white
                    self.btnContinue.setTitleColor(Colors.red, for: .normal)
                    self.btnContinue.setTitle("retry".localized(), for: .normal)
                }, completion: { (Bool) -> Void in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        
                        UIView.transition(with: self.btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                            
                            self.btnContinue.setTitleColor(Colors.black, for: .normal)
                            self.btnContinue.backgroundColor = Colors.white
                            
                            if self.wrongAttempts > 5
                            {
                                self.btnShowAns.isHidden = false
                                self.btnShowAns.setTitleColor(Colors.black, for: .normal)
                                self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                            }
                            else
                            {
                                self.btnContinue.setTitleColor(Colors.black, for: .normal)
                                
                                if (self.arrType4Questions[self.questionIndex]["option"].exists())
                                {
                                    self.btnContinue.setTitle("chooseOption".localized(), for: .normal)
                                }
                                else
                                {
                                    self.btnContinue.setTitle("typeAnswer".localized(), for: .normal)
                                }
                            }
                        })
                    }
                })
                
                wrongIndex = indexPath.row
                cvOptions.reloadData()
            }
            
            if strSelectedWord == strWord
            {
                if attempts == strWord.count {
                    score += 1
                }
                
                TapticEngine.notification.feedback(.success)
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnContinue.backgroundColor = Colors.green
                    self.btnContinue.setTitleColor(Colors.white, for: .normal)
                    self.btnContinue.setTitle("correct".localized(), for: .normal)
                }, completion: { (Bool) -> Void in
                    self.questionIndex += 1
                    
                    if (self.arrType4Questions.count > self.questionIndex) {
                        UIView.animate(withDuration: 0.5, animations: {
                            let animation = CATransition()
                            animation.duration = 1.0
                            animation.startProgress = 0.0
                            animation.endProgress = 1.0
                            animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                            animation.type = "pageCurl"
                            animation.subtype = "fromBottom"
                            animation.isRemovedOnCompletion = false
                            animation.fillMode = "extended"
                            self.view.layer.add(animation, forKey: "pageFlipAnimation")
                        })
                        self.updateQuestion()
                    }
                    else
                    {
                        self.questionIndex -= 1
                        
                        let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                        objExComplete.score = self.score
                        objExComplete.totalQuestions = self.arrType4Questions.count
                        self.navigationController?.pushViewController(objExComplete, animated: true)
                    }
                })
            }
        }
    }
}

extension ExType4VC: UITextFieldDelegate {
    
    @objc func textDidChanged(textField: UITextField) {
        
        if (textField.text?.count)! > 0 {
            
            attempts += 1
            
            if (textField.text?.lowercased()) == (String(strWord[strWord.index(strWord.startIndex, offsetBy: textField.tag)]).lowercased()) {
                
                textField.textColor = UIColor.black
                strSelectedWord = strSelectedWord.appending(textField.text!)
                
                cvAnswer.selectItem(at: IndexPath(item: textField.tag, section: 0), animated: true, scrollPosition: .bottom)
                
                let nextIndex = Int(textField.tag) + 1
                
                if nextIndex < strWord.count {
                    let cell = cvAnswer.cellForItem(at: IndexPath(item: nextIndex, section: 0)) as! CellAnswers
                    
                    cell.tfAnswer.becomeFirstResponder()
                    
                    UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                        self.btnContinue.setTitleColor(Colors.white, for: .normal)
                        self.btnContinue.backgroundColor = Colors.green
                        self.btnContinue.setTitle("correct".localized(), for: .normal)
                    }, completion: { (Bool) -> Void in
                        
                        DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                            
                            UIView.transition(with: self.btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                                
                                if self.wrongAttempts > 5
                                {
                                    self.btnShowAns.backgroundColor = Colors.white
                                    self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                                    self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                                }
                                else
                                {
                                    self.btnShowAns.backgroundColor = Colors.white
                                    self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                                    self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                                    
                                    self.btnContinue.setTitleColor(Colors.black, for: .normal)
                                    self.btnContinue.backgroundColor = Colors.white
                                    
                                    if (self.arrType4Questions[self.questionIndex]["option"].exists())
                                    {
                                        self.btnContinue.setTitle("chooseOption".localized(), for: .normal)
                                    }
                                    else
                                    {
                                        self.btnContinue.setTitle("typeAnswer".localized(), for: .normal)
                                    }
                                }
                                
                            }, completion: nil)
                        }
                    })
                }
            }
            else
            {
                TapticEngine.notification.feedback(.error)
                
                wrongAttempts += 1
                
                if wrongAttempts == 5
                {
                    btnContinue.isUserInteractionEnabled = true
                }
                
                textField.textColor = Colors.red
                textField.shake(pixel: 3)
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnContinue.backgroundColor = Colors.white
                    self.btnContinue.setTitleColor(Colors.red, for: .normal)
                    self.btnContinue.setTitle("retry".localized(), for: .normal)
                }, completion: { (Bool) -> Void in
                    
                    DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                        
                        UIView.transition(with: self.btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                            
                            self.btnContinue.backgroundColor = Colors.white
                            self.btnContinue.setTitleColor(Colors.black, for: .normal)
                            
                            if self.wrongAttempts > 5
                            {
                                self.btnShowAns.isHidden = false
                                self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                                self.btnShowAns.setTitle("showCorrectAnswer".localized(), for: .normal)
                            }
                            else
                            {
                                self.btnContinue.setTitleColor(Colors.black, for: .normal)
                                
                                if (self.arrType4Questions[self.questionIndex]["option"].exists())
                                {
                                    self.btnContinue.setTitle("chooseOption".localized(), for: .normal)
                                }
                                else
                                {
                                    self.btnContinue.setTitle("typeAnswer".localized(), for: .normal)
                                }
                            }
                        })
                        
                    }
                })
                
                cvOptions.reloadData()
            }
            
            if strSelectedWord.lowercased() == strWord.lowercased()
            {
                self.view.endEditing(true)
                
                TapticEngine.notification.feedback(.success)
                
                if attempts == strWord.count {
                    score += 1
                }
                
                UIView.transition(with: btnContinue, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnContinue.backgroundColor = Colors.green
                    self.btnContinue.setTitleColor(Colors.white, for: .normal)
                    self.btnContinue.setTitle("correct".localized(), for: .normal)
                }, completion: { (Bool) -> Void in
                    
                    self.questionIndex += 1
                    
                    if (self.arrType4Questions.count > self.questionIndex)
                    {
                        UIView.animate(withDuration: 0.5, animations: {
                            let animation = CATransition()
                            animation.duration = 1.0
                            animation.startProgress = 0.0
                            animation.endProgress = 1.0
                            self.btnShowAns.isHidden = true
                            self.btnShowAns.setTitle("Show Correct Answer".localized(), for: .normal)
                            self.btnShowAns.backgroundColor = Colors.white
                            self.btnShowAns.setTitleColor(Colors.green, for: .normal)
                            animation.type = "pageCurl"
                            animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                            animation.type = "pageCurl"
                            animation.subtype = "fromBottom"
                            animation.isRemovedOnCompletion = false
                            animation.fillMode = "extended"
                            self.view.layer.add(animation, forKey: "pageFlipAnimation")
                        })
                        self.updateQuestion()
                    }
                    else
                    {
                        self.questionIndex -= 1
                        
                        let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                        objExComplete.score = self.score
                        objExComplete.totalQuestions = self.arrType4Questions.count
                        self.navigationController?.pushViewController(objExComplete, animated: true)
                    }
                })
            }
        }
    }
    
    func textFieldDidBeginEditing(_ textField: UITextField) {
        isUIUpdateRequired = false
        
        if !(textField.textInputMode?.primaryLanguage?.hasPrefix("sv"))! && !isAlertDisplayed {
            
            UserDefaults.standard.set( true, forKey: "type4KeyboardAlert")
            UserDefaults.standard.synchronize()
            
            isAlertDisplayed = true
        }
        
        let cell = self.cvAnswer.cellForItem(at: IndexPath(item: strSelectedWord.count, section: 0)) as! CellAnswers
        
        DispatchQueue.main.asyncAfter(deadline: .now() + 0.01) {
            cell.tfAnswer.becomeFirstResponder()
        }
    }
    
    func textFieldShouldEndEditing(_ textField: UITextField) -> Bool {
        true
    }
    
    func textField(_ textField: UITextField, shouldChangeCharactersIn range: NSRange, replacementString string: String) -> Bool {
        textField.text = ""
        return true
    }
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
}
