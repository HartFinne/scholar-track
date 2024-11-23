import sys
from paddleocr import PaddleOCR
import concurrent.futures

def extract_text(image_path):
    # Initialize PaddleOCR with language 'en' (English)
    ocr = PaddleOCR(use_angle_cls=True, lang='en')
    result = ocr.ocr(image_path, cls=True)

    extracted_text = ''
    for line in result[0]:
        word_info = line[1]
        word_text = word_info if isinstance(word_info, str) else str(word_info[0])
        extracted_text += word_text + '\n'

    return extracted_text

def process_images(image_paths):
    with concurrent.futures.ThreadPoolExecutor() as executor:
        # Use the executor to process images concurrently
        results = list(executor.map(extract_text, image_paths))
    return results

if __name__ == '__main__':
    # Assuming you provide a list of image paths
    image_paths = sys.argv[1:]  # Get a list of image paths from command line
    texts = process_images(image_paths)
    
    # Print out the extracted text for each image
    for text in texts:
        print(text)



